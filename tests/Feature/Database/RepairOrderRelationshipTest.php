
<?php

use App\Models\Tenant;
use App\Models\Vehicle;
use App\Models\RepairOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('repair order is correctly associated with tenant, customer vehicle and employee', function () {

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

    $employee = $tenant->users()->create([
        'name' => 'Test Employee',
        'email' => 'employee@example.test',
        'password' => 'password123',
    ]);

    $repairOrder = new RepairOrder([
        'customer_complaint' => 'Variklis skleidžia neįprastą garsą',
        'status' => 'pending',
    ]);

    $repairOrder->tenant()->associate($tenant);
    $repairOrder->customerVehicle()->associate($customerVehicle);
    $repairOrder->assignedEmployee()->associate($employee);

    $repairOrder->save();

    $this->assertDatabaseHas('repair_orders', [
        'id' => $repairOrder->id,
        'tenant_id' => $tenant->id,
        'customer_vehicle_id' => $customerVehicle->id,
        'assigned_to' => $employee->id,
    ]);

    expect($repairOrder->tenant->id)->toBe($tenant->id);

    expect($repairOrder->customerVehicle->id)
        ->toBe($customerVehicle->id);

    expect($repairOrder->assignedEmployee->id)
        ->toBe($employee->id);

    expect($employee->assignedRepairOrders()->first()->id)
        ->toBe($repairOrder->id);
});

