
<?php

use App\Models\Tenant;
use App\Models\Vehicle;
use App\Models\RepairOrder;
use App\Models\Estimate;
use App\Models\EstimateItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('estimate is correctly associated with repair order employee and items', function () {

    $tenant = Tenant::create([
        'name' => 'Test Service',
        'email' => 'service@example.test',
        'slug' => 'test-service',
    ]);

    $employee = $tenant->users()->create([
        'name' => 'Test Employee',
        'email' => 'employee@example.test',
        'password' => 'password123',
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
    ]);

    $repairOrder = new RepairOrder([
        'customer_complaint' => 'Variklis skleidžia neįprastą garsą',
        'status' => 'awaiting_approval',
    ]);

    $repairOrder->tenant()->associate($tenant);
    $repairOrder->customerVehicle()->associate($customerVehicle);
    $repairOrder->assignedEmployee()->associate($employee);
    $repairOrder->save();

    $estimate = new Estimate();

    $estimate->version = 1;
    $estimate->status = 'draft';
    $estimate->total_amount = 150.00;

    $estimate->repairOrder()->associate($repairOrder);
    $estimate->creator()->associate($employee);

    $estimate->save();

    $item = new EstimateItem();

    $item->type = 'labor';
    $item->description = 'Variklio grandinės keitimas';
    $item->quantity = 1;
    $item->unit_price = 150.00;
    $item->total_price = 150.00;

    $estimate->items()->save($item);

    $this->assertDatabaseHas('estimates', [
        'id' => $estimate->id,
        'repair_order_id' => $repairOrder->id,
        'created_by' => $employee->id,
    ]);

    expect($estimate->repairOrder->id)
        ->toBe($repairOrder->id);

    expect($estimate->creator->id)
        ->toBe($employee->id);

    expect($estimate->items()->first()->id)
        ->toBe($item->id);

    expect($item->estimate->id)
        ->toBe($estimate->id);
});
