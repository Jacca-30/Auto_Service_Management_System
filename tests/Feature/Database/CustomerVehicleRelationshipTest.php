
<?php

use App\Models\Tenant;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can be associated with a vehicle', function () {

    $tenant = Tenant::create([
        'name' => 'Test Service',
        'email' => 'service@example.test',
        'slug' => 'test-service',
    ]);

    $customer = $tenant->customers()->create([
        'name' => 'Jonas Jonaitis',
        'email' => 'jonas@example.test',
        'phone' => '860000000',
    ]);

    $vehicle = Vehicle::create([
        'make' => 'BMW',
        'model' => '530',
    ]);

    $customerVehicle = $customer->customerVehicles()->create([
        'vehicle_id' => $vehicle->id,
        'license_plate' => 'ABC123',
        'reg_cert_number' => 'REG123',
    ]);

    $this->assertDatabaseHas('customer_vehicles', [
        'id' => $customerVehicle->id,
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
    ]);

    expect($customer->customerVehicles()->first()->vehicle->id)
        ->toBe($vehicle->id);

    expect($vehicle->customerVehicles()->first()->customer->id)
        ->toBe($customer->id);
});

