<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\RepairOrder;
use App\Scopes\TenantScope;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PublicRepairRequestController extends Controller
{
    public function store(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            // Kliento duomenys
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],

            // Automobilio duomenys
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1886', 'max:' . (date('Y') + 1)],
            'fuel_type' => ['required', 'string', 'max:50'],
            'engine_code' => ['nullable', 'string', 'max:50'],
            'engine_displacement_cm3' => ['nullable', 'integer', 'min:1'],
            'engine_power_kw' => ['nullable', 'integer', 'min:1'],
            'transmission_type' => ['required', 'string', 'max:50'],

            // Automobilio identifikacija
            'vin' => ['nullable', 'string', 'max:17'],
            'license_plate' => ['required', 'string', 'max:20'],
            'reg_cert_number' => ['nullable', 'string', 'max:100'],

            // Gedimo aprašymas
            'customer_complaint' => ['required', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($validated, $tenant) {

            $customer = Customer::withoutGlobalScope(TenantScope::class)
                ->where('tenant_id', $tenant->id)
                ->where('email', $validated['email'])
                ->first();

            if (!$customer) {
                $customer = new Customer();
                $customer->tenant_id = $tenant->id;
            }

            $customer->name = $validated['name'];
            $customer->email = $validated['email'];
            $customer->phone = $validated['phone'];
            $customer->save();

            $vehicleData = [
                'make' => $validated['make'],
                'model' => $validated['model'],
                'year' => $validated['year'],
                'fuel_type' => $validated['fuel_type'],
                'engine_code' => $validated['engine_code'] ?? null,
                'engine_displacement_cm3' => $validated['engine_displacement_cm3'] ?? null,
                'engine_power_kw' => $validated['engine_power_kw'] ?? null,
                'transmission_type' => $validated['transmission_type'],
            ];

            $vehicle = Vehicle::firstOrCreate($vehicleData);

            $customerVehicle = $customer->customerVehicles()->create([
                'vehicle_id' => $vehicle->id,
                'vin' => $validated['vin'] ?? null,
                'license_plate' => $validated['license_plate'],
                'reg_cert_number' => $validated['reg_cert_number'] ?? null,
            ]);

            $repairOrder = new RepairOrder();

            $repairOrder->tenant_id = $tenant->id;
            $repairOrder->customer_vehicle_id = $customerVehicle->id;
            $repairOrder->status = 'pending';
            $repairOrder->customer_complaint = $validated['customer_complaint'];

            $repairOrder->save();
        });

        return back()->with(
            'success',
            'Jūsų remonto užklausa sėkmingai pateikta!'
        );
    }
}
