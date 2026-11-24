
<?php

use App\Models\Tenant;
use App\Models\Vehicle;
use App\Models\RepairOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('diagnostic is correctly associated with repair order and employee', function () {

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
        'status' => 'diagnosing',
    ]);

    $repairOrder->tenant()->associate($tenant);
    $repairOrder->customerVehicle()->associate($customerVehicle);
    $repairOrder->assignedEmployee()->associate($employee);
    $repairOrder->save();

    $diagnostic = $repairOrder->diagnostics()->create([
        'performed_by' => $employee->id,
        'findings' => 'Nustatytas variklio grandinės gedimas',
        'recommendations' => 'Rekomenduojama pakeisti grandinę',
    ]);

    $this->assertDatabaseHas('diagnostics', [
        'id' => $diagnostic->id,
        'repair_order_id' => $repairOrder->id,
        'performed_by' => $employee->id,
    ]);

    expect($diagnostic->repairOrder->id)
        ->toBe($repairOrder->id);

    // Tikriname diagnostiką atlikusį darbuotoją
    expect($diagnostic->performedBy->id)
        ->toBe($employee->id);

    // Tikriname atvirkštinį ryšį su diagnostika
    expect($repairOrder->diagnostics()->first()->id)
        ->toBe($diagnostic->id);
});
