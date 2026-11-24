<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DiagnosticController extends Controller
{
    public function store(Request $request, int $repairOrderId): RedirectResponse
    {
        $validated = $request->validate([
            'findings' => ['required', 'string', 'max:5000'],
            'recommendations' => ['nullable', 'string', 'max:5000'],
        ]);

        $repairOrder = RepairOrder::query()
            ->forTenant($request->user()->tenant_id)
            ->findOrFail($repairOrderId);

        if (!in_array($repairOrder->status, ['accepted', 'diagnosing'], true)) {
            return back()->with(
                'error',
                'Diagnostiką galima atlikti tik priimtam arba diagnozuojamam užsakymui.'
            );
        }

        DB::transaction(function () use ($repairOrder, $validated, $request) {
            $repairOrder->diagnostics()->create([
                'performed_by' => $request->user()->id,
                'findings' => $validated['findings'],
                'recommendations' => $validated['recommendations'] ?? null,
            ]);

            $repairOrder->status = 'diagnosing';
            $repairOrder->save();
        });

        return back()->with(
            'success',
            'Diagnostika sėkmingai išsaugota. Užsakymas laukia sąmatos sudarymo.'
        );
    }
}
