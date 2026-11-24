<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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

        return view('estimates.create', compact('repairOrder'));
    }

    public function store(Request $request, int $repairOrderId): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
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

        if ($repairOrder->status !== 'diagnosing' ||
            $repairOrder->diagnostics()->doesntExist()) {
            return back()->with(
                'error',
                'Sąmatą galima kurti tik atlikus diagnostiką.'
            );
        }

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
}
