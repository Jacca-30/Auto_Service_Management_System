<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerVehicleController extends Controller
{
    public function show(int $customerId): View
    {
        $customer = Customer::with('customerVehicles.vehicle')
            ->findOrFail($customerId);

        return view('customers.show', compact('customer'));
    }

    public function create(int $customerId): View
    {
        $customer = Customer::findOrFail($customerId);

        return view('customer-vehicles.create', compact('customer'));
    }

    public function store(Request $request, int $customerId): RedirectResponse
    {
        $customer = Customer::findOrFail($customerId);

        $validated = $request->validate([
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1886', 'max:' . (date('Y') + 1)],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'engine_code' => ['nullable', 'string', 'max:50'],
            'engine_displacement_cm3' => ['nullable', 'integer', 'min:1'],
            'engine_power_kw' => ['nullable', 'integer', 'min:1'],
            'transmission_type' => ['nullable', 'string', 'max:50'],

            'vin' => ['nullable', 'string', 'max:17'],
            'license_plate' => ['nullable', 'string', 'max:20'],
            'reg_cert_number' => ['nullable', 'string', 'max:100'],
        ]);

        $vehicleData = [
            'make' => $validated['make'],
            'model' => $validated['model'],
            'year' => $validated['year'] ?? null,
            'fuel_type' => $validated['fuel_type'] ?? null,
            'engine_code' => $validated['engine_code'] ?? null,
            'engine_displacement_cm3' => $validated['engine_displacement_cm3'] ?? null,
            'engine_power_kw' => $validated['engine_power_kw'] ?? null,
            'transmission_type' => $validated['transmission_type'] ?? null,
        ];

        $vehicle = Vehicle::firstOrCreate($vehicleData);

        $customer->customerVehicles()->create([
            'vehicle_id' => $vehicle->id,
            'vin' => $validated['vin'] ?? null,
            'license_plate' => $validated['license_plate'] ?? null,
            'reg_cert_number' => $validated['reg_cert_number'] ?? null,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Automobilis sėkmingai pridėtas klientui.');
    }
}
