<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RepairOrderController extends Controller
{
    public function index(Request $request): View
    {
        $repairOrders = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->with([
                'customerVehicle.customer',
                'customerVehicle.vehicle',
                'assignedEmployee',
            ])
            ->latest()
            ->paginate(15);

        return view('repair-orders.index', compact('repairOrders'));
    }

    public function show(Request $request, int $id): View
    {
        $repairOrder = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->with([
                'customerVehicle.customer',
                'customerVehicle.vehicle',
                'assignedEmployee',
                'diagnostics',
                'estimates',
            ])
            ->findOrFail($id);

        return view('repair-orders.show', compact('repairOrder'));
    }

    public function accept(Request $request, int $id): RedirectResponse
    {
        $repairOrder = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->findOrFail($id);

        if ($repairOrder->status !== 'pending') {
            return back()->with(
                'error',
                'Galima priimti tik laukiančią užklausą.'
            );
        }

        $repairOrder->status = 'accepted';
        $repairOrder->assigned_to = $request->user()->id;
        $repairOrder->save();

        return back()->with(
            'success',
            'Remonto užklausa sėkmingai priimta.'
        );
    }

    public function decline(Request $request, int $id): RedirectResponse
    {
        $repairOrder = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->findOrFail($id);

        if ($repairOrder->status !== 'pending') {
            return back()->with(
                'error',
                'Galima atmesti tik laukiančią užklausą.'
            );
        }

        $repairOrder->status = 'declined';
        $repairOrder->save();

        return back()->with(
            'success',
            'Remonto užklausa sėkmingai atmesta.'
        );
    }

    public function complete(Request $request, int $id): RedirectResponse
    {
        $completed = DB::transaction(function () use ($request, $id) {
            $repairOrder = RepairOrder::query()
                ->forTenant($request->user()->tenant_id)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($repairOrder->status !== 'in_repair') {
                return false;
            }

            $estimate = $repairOrder->estimates()
                ->where('is_current', true)
                ->where('status', 'approved')
                ->with('items')
                ->first();

            if (!$estimate) {
                throw ValidationException::withMessages([
                    'estimate' => 'Negalima užbaigti remonto be patvirtintos aktualios sąmatos.',
                ]);
            }

            $estimateItems = $estimate?->items ?? collect();

            // Grupavimas pagal inventoriaus prekę.
            $requiredQuantities = $estimateItems
                ->filter(fn ($item) =>
                    $item->inventory_item_id !== null &&
                    in_array($item->type, ['part', 'material'])
                )
                ->groupBy('inventory_item_id')
                ->map(fn ($items) => (float) $items->sum('quantity'));

            foreach ($requiredQuantities as $inventoryItemId => $requiredQuantity) {
                $item = InventoryItem::query()
                    ->lockForUpdate()
                    ->find($inventoryItemId);

                if (!$item) {
                    throw ValidationException::withMessages([
                        'inventory' => 'Viena iš sąmatoje pasirinktų inventoriaus prekių neberasta.',
                    ]);
                }

                // Patikriname, kiek šios prekės jau nurašyta
                // rankiniu būdu šiam remonto užsakymui.
                $alreadyUsed = InventoryMovement::query()
                    ->where('repair_order_id', $repairOrder->id)
                    ->where('inventory_item_id', $item->id)
                    ->where('type', 'used')
                    ->sum('quantity');

                // Nurašome tik dar nenurašytą kiekį.
                $remaining = round(
                    $requiredQuantity - (float) $alreadyUsed,
                    2
                );

                if ($remaining <= 0) {
                    continue;
                }

                if ((float) $item->quantity < $remaining) {
                    throw ValidationException::withMessages([
                        'inventory' => 'Nepakanka prekės „' . $item->name .
                            '“. Reikia: ' . $remaining . ' ' . $item->unit .
                            ', turima: ' . $item->quantity . ' ' . $item->unit . '.',
                    ]);
                }

                $item->quantity -= $remaining;
                $item->save();

                $movement = new InventoryMovement();
                $movement->tenant_id = $request->user()->tenant_id;
                $movement->inventory_item_id = $item->id;
                $movement->repair_order_id = $repairOrder->id;
                $movement->performed_by = $request->user()->id;
                $movement->type = 'used';
                $movement->quantity = $remaining;
                $movement->notes = 'Automatinis nurašymas užbaigus remontą.';
                $movement->save();
            }

            $repairOrder->status = 'completed';
            $repairOrder->save();

            return true;
        });

        if (!$completed) {
            return back()->with(
                'error',
                'Užbaigti galima tik šiuo metu remontuojamą automobilį.'
            );
        }

        return back()->with(
            'success',
            'Remontas užbaigtas, inventoriaus prekės nurašytos.'
        );
    }
}
