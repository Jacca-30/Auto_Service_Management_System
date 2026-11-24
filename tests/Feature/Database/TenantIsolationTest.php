
<?php

use App\Models\User;
use App\Models\Tenant;
use App\Models\Vehicle;
use App\Models\RepairOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tenant can only retrieve its own repair orders', function () {

    // Sukuriame du autoservisus
    $tenantA = Tenant::create([
        'name' => 'Autoservisas A',
        'email' => 'a@example.test',
        'slug' => 'service-a',
    ]);

    $tenantB = Tenant::create([
        'name' => 'Autoservisas B',
        'email' => 'b@example.test',
        'slug' => 'service-b',
    ]);

    // Sukuriame klientą autoservisui A
    $customerA = $tenantA->customers()->create([
        'name' => 'Jonas',
        'email' => 'jonas@example.test',
        'phone' => '860000001',
    ]);

    // Sukuriame klientą autoservisui B
    $customerB = $tenantB->customers()->create([
        'name' => 'Petras',
        'email' => 'petras@example.test',
        'phone' => '860000002',
    ]);

    // Sukuriame automobilius
    $vehicleA = Vehicle::create([
        'make' => 'BMW',
        'model' => '530',
    ]);

    $vehicleB = Vehicle::create([
        'make' => 'Audi',
        'model' => 'A4',
    ]);

    // Susiejame klientus su automobiliais
    $customerVehicleA = $customerA->customerVehicles()->create([
        'vehicle_id' => $vehicleA->id,
    ]);

    $customerVehicleB = $customerB->customerVehicles()->create([
        'vehicle_id' => $vehicleB->id,
    ]);

    // Sukuriame remonto užsakymą autoservisui A
    $orderA = new RepairOrder([
        'customer_complaint' => 'Variklio gedimas',
        'status' => 'pending',
    ]);

    $orderA->tenant()->associate($tenantA);
    $orderA->customerVehicle()->associate($customerVehicleA);
    $orderA->save();

    // Sukuriame remonto užsakymą autoservisui B
    $orderB = new RepairOrder([
        'customer_complaint' => 'Stabdžių gedimas',
        'status' => 'pending',
    ]);

    $orderB->tenant()->associate($tenantB);
    $orderB->customerVehicle()->associate($customerVehicleB);
    $orderB->save();

    // Tikriname, ar autoservisas A mato savo užsakymą
    expect(
        RepairOrder::where('tenant_id', $tenantA->id)
            ->find($orderA->id)
    )->not->toBeNull();

    // Tikriname, ar autoservisas A negali rasti B užsakymo
    expect(
        RepairOrder::where('tenant_id', $tenantA->id)
            ->find($orderB->id)
    )->toBeNull();

    // Tikriname, ar autoservisas B negali rasti A užsakymo
    expect(
        RepairOrder::where('tenant_id', $tenantB->id)
            ->find($orderA->id)
    )->toBeNull();

    // Tikriname, ar autoservisas B mato savo užsakymą
    expect(
        RepairOrder::where('tenant_id', $tenantB->id)
            ->find($orderB->id)
    )->not->toBeNull();

    /*
    $employeeA = new User();
    $employeeA->name = 'Darbuotojas A';
    $employeeA->email = 'employee-a@example.test';
    $employeeA->password = 'Password123!';
    $employeeA->tenant_id = $tenantA->id;
    $employeeA->role = 'employee';
    $employeeA->save();

    $this->actingAs($employeeA);

    $response = $this->get('/repair-orders');

    $response->assertOk();
    $response->assertSee('Variklio gedimas');
    $response->assertDontSee('Stabdžių gedimas');
    */
});

