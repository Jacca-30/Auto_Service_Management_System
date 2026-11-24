<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
}
