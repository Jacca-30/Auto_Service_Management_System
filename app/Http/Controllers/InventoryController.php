<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $inventoryItems = InventoryItem::query()
            ->orderBy('name')
            ->paginate(15);

        $repairOrders = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->where('status', 'in_repair')
            ->with([
                'customerVehicle.customer',
                'customerVehicle.vehicle',
            ])
            ->latest()
            ->get();

        return view('inventory.index', compact(
            'inventoryItems',
            'repairOrders'
        ));
    }

    public function create(): View
    {
        return view('inventory.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:part,material'],
            'name' => ['required', 'string', 'max:255'],
            'oem_number' => ['nullable', 'string', 'max:255'],
            'ap_number' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'minimum_quantity' => ['required', 'numeric', 'min:0'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
        ]);

        $item = new InventoryItem($validated);
        $item->tenant_id = $request->user()->tenant_id;
        $item->quantity = 0;
        $item->save();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Inventoriaus prekė sėkmingai pridėta.');
    }

    public function receive(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'numeric', 'gt:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $id, $validated) {
            $item = InventoryItem::query()
                ->lockForUpdate()
                ->findOrFail($id);

            $item->quantity += $validated['quantity'];
            $item->save();

            $movement = new InventoryMovement();
            $movement->tenant_id = $request->user()->tenant_id;
            $movement->inventory_item_id = $item->id;
            $movement->performed_by = $request->user()->id;
            $movement->type = 'received';
            $movement->quantity = $validated['quantity'];
            $movement->notes = $validated['notes'] ?? null;
            $movement->save();
        });

        return back()->with('success', 'Atsargos sėkmingai papildytos.');
    }

    public function consume(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'repair_order_id' => ['required', 'integer'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $id, $validated) {

            $repairOrder = RepairOrder::query()
                ->forTenant($request->user()->tenant_id)
                ->findOrFail($validated['repair_order_id']);

            if ($repairOrder->status !== 'in_repair') {
                throw ValidationException::withMessages([
                    'repair_order_id' => 'Dalys gali būti naudojamos tik vykdomam remontui.',
                ]);
            }

            $item = InventoryItem::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if ((float) $item->quantity < (float) $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Nepakanka atsargų. Turimas kiekis: '
                        . $item->quantity . ' ' . $item->unit,
                ]);
            }

            $item->quantity -= $validated['quantity'];
            $item->save();

            $movement = new InventoryMovement();
            $movement->tenant_id = $request->user()->tenant_id;
            $movement->inventory_item_id = $item->id;
            $movement->repair_order_id = $repairOrder->id;
            $movement->performed_by = $request->user()->id;
            $movement->type = 'used';
            $movement->quantity = $validated['quantity'];
            $movement->notes = $validated['notes'] ?? null;
            $movement->save();
        });

        return back()->with(
            'success',
            'Prekės panaudojimas sėkmingai užregistruotas.'
        );
    }

    public function show(int $id): View
    {
        $item = InventoryItem::query()
            ->findOrFail($id);

        $movements = $item->movements()
            ->with(['repairOrder', 'performedBy'])
            ->latest()
            ->paginate(20);

        return view('inventory.show', compact(
            'item',
            'movements'
        ));
    }
}
