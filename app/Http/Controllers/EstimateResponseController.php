<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EstimateResponseController extends Controller
{
    public function show(string $token): View
    {
        $estimate = Estimate::query()
            ->where('approval_token_hash', hash('sha256', $token))
            ->where('is_current', true)
            ->where('status', 'sent')
            ->where('expires_at', '>', now())
            ->with([
                'items',
                'repairOrder.customerVehicle.customer',
                'repairOrder.customerVehicle.vehicle',
            ])
            ->firstOrFail();

        return view('estimates.response', compact('estimate', 'token'));
    }

    public function approve(Request $request, string $token): RedirectResponse
    {
        return $this->respond($token, 'approved');
    }

    public function reject(Request $request, string $token): RedirectResponse
    {
        return $this->respond($token, 'rejected');
    }

    private function respond(string $token, string $status): RedirectResponse
    {
        $result = DB::transaction(function () use ($token, $status) {
            $estimate = Estimate::query()
                ->where('approval_token_hash', hash('sha256', $token))
                ->lockForUpdate()
                ->first();

            if (
                !$estimate ||
                !$estimate->is_current ||
                $estimate->status !== 'sent' ||
                !$estimate->expires_at ||
                $estimate->expires_at->isPast()
            ) {
                return false;
            }

            $repairOrder = $estimate->repairOrder()
                ->lockForUpdate()
                ->first();

            if (!$repairOrder || $repairOrder->status !== 'awaiting_approval') {
                return false;
            }

            $estimate->status = $status;

            if ($status === 'approved') {
                $estimate->approved_at = now();
                $repairOrder->status = 'in_repair';
            } else {
                $estimate->rejected_at = now();
                $repairOrder->status = 'diagnosing';
            }

            $estimate->approval_token_hash = null;

            $estimate->save();
            $repairOrder->save();

            return true;
        });

        if (!$result) {
            return redirect()->route('estimates.result', [
                'status' => 'invalid',
            ]);
        }

        return redirect()->route('estimates.result', [
            'status' => $status,
        ]);
    }

    public function result(string $status): View
    {
        abort_unless(
            in_array($status, ['approved', 'rejected', 'invalid'], true),
            404
        );

        return view('estimates.result', compact('status'));
    }
}
