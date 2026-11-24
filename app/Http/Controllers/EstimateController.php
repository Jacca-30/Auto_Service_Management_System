<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Mail\EstimateSentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Estimate;
use App\Models\InventoryItem;

class EstimateController extends Controller
{
    public function create(Request $request, int $repairOrderId): View
    {
        $repairOrder = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->with(['customerVehicle.customer', 'customerVehicle.vehicle'])
            ->findOrFail($repairOrderId);

        if ($repairOrder->status !== 'diagnosing' ||
            $repairOrder->diagnostics()->doesntExist()) {
            abort(403, 'Sąmatą galima kurti tik atlikus diagnostiką.');
        }

        $inventoryItems = InventoryItem::query()
            ->orderBy('name')
            ->get();

        return view('estimates.create', compact('repairOrder', 'inventoryItems'));
    }

    public function store(Request $request, int $repairOrderId): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.inventory_item_id' => ['nullable', 'integer'],
            'items.*.type' => ['required', 'in:labor,part,material'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.oem_number' => ['nullable', 'string', 'max:255'],
            'items.*.ap_number' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $repairOrder = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->findOrFail($repairOrderId);

        if (
            $repairOrder->status !== 'diagnosing' ||
            $repairOrder->diagnostics()->doesntExist()
        ) {
            return back()->with(
                'error',
                'Sąmatą galima kurti tik atlikus diagnostiką.'
            );
        }

        foreach ($validated['items'] as $index => &$item) {
            $inventoryItem = null;

            if (!empty($item['inventory_item_id'])) {
                $inventoryItem = InventoryItem::query()
                    ->find($item['inventory_item_id']);

                if (!$inventoryItem) {
                    throw ValidationException::withMessages([
                        "items.$index.inventory_item_id" =>
                            'Pasirinkta inventoriaus prekė nerasta arba nepriklauso jūsų autoservisui.',
                    ]);
                }

                // Duomenis paimame iš inventoriaus, o ne iš naršyklės.
                $item['type'] = $inventoryItem->type;
                $item['description'] = $inventoryItem->name;
                $item['oem_number'] = $inventoryItem->oem_number;
                $item['ap_number'] = $inventoryItem->ap_number;
            } else {
                $item['inventory_item_id'] = null;
            }
        }

        unset($item);

        DB::transaction(function () use ($repairOrder, $validated, $request) {
            $previousEstimate = $repairOrder->estimates()
                ->where('is_current', true)
                ->lockForUpdate()
                ->first();

            $version = $repairOrder->estimates()->max('version') + 1;

            if ($previousEstimate) {
                $previousEstimate->is_current = false;

                if (in_array($previousEstimate->status, ['sent', 'approved'])) {
                    $previousEstimate->status = 'expired';
                }

                $previousEstimate->save();
            }

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = round(
                    (float) $item['quantity'] * (float) $item['unit_price'],
                    2
                );

                $totalAmount += $lineTotal;
            }

            $estimate = $repairOrder->estimates()->make();

            $estimate->created_by = $request->user()->id;
            $estimate->version = $version;
            $estimate->status = 'draft';
            $estimate->total_amount = $totalAmount;
            $estimate->is_current = true;
            $estimate->save();

            foreach ($validated['items'] as $item) {
                $lineTotal = round(
                    (float) $item['quantity'] * (float) $item['unit_price'],
                    2
                );

                $estimateItem = $estimate->items()->make([
                    'inventory_item_id' => $item['inventory_item_id'],
                    'type' => $item['type'],
                    'description' => $item['description'],
                    'oem_number' => $item['oem_number'] ?? null,
                    'ap_number' => $item['ap_number'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);

                $estimateItem->total_price = $lineTotal;
                $estimateItem->save();
            }
        });

        return redirect()
            ->route('repair-orders.show', $repairOrderId)
            ->with('success', 'Sąmata sėkmingai sukurta.');
    }



    public function send(Request $request, int $estimateId): RedirectResponse
    {
        $estimate = Estimate::query()
            ->whereHas('repairOrder', function ($query) use ($request) {
                $query->forTenant($request->user()->tenant_id);
            })
            ->with([
                'items',
                'repairOrder.customerVehicle.customer',
                'repairOrder.customerVehicle.vehicle',
            ])
            ->findOrFail($estimateId);

        if (!$estimate->is_current || $estimate->status !== 'draft') {
            return back()->with('error', 'Galima siųsti tik aktualią nepatvirtintą sąmatą.');
        }

        if ($estimate->repairOrder->status !== 'diagnosing') {
            return back()->with('error', 'Sąmatą galima siųsti tik diagnostikos etape.');
        }

        $customer = $estimate->repairOrder->customerVehicle->customer;

        $token = Str::random(64);

        $estimate->approval_token_hash = hash('sha256', $token);
        $estimate->expires_at = now()->addDays(1);
        $estimate->save();

        $approvalUrl = route('estimates.respond', ['token' => $token]);

        Mail::to($customer->email)->send(
            new EstimateSentMail($estimate, $approvalUrl)
        );

        $estimate->status = 'sent';
        $estimate->sent_at = now();
        $estimate->save();

        $repairOrder = $estimate->repairOrder;
        $repairOrder->status = 'awaiting_approval';
        $repairOrder->save();

        return back()->with('success', 'Sąmata sėkmingai išsiųsta klientui.');
    }

}
