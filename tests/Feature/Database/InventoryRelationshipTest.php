
<?php

use App\Models\Tenant;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('inventory item is correctly associated with tenant and movements', function () {

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

    $inventoryItem = new InventoryItem([
        'type' => 'part',
        'name' => 'Alyvos filtras',
        'unit' => 'vnt.',
        'minimum_quantity' => 2,
        'purchase_price' => 5.00,
    ]);

    $inventoryItem->tenant()->associate($tenant);
    $inventoryItem->quantity = 10;
    $inventoryItem->save();

    $movement = new InventoryMovement([
        'type' => 'received',
        'quantity' => 10,
        'notes' => 'Gauta nauja prekių siunta',
    ]);

    $movement->inventoryItem()->associate($inventoryItem);
    $movement->tenant()->associate($tenant);
    $movement->performedBy()->associate($employee);
    $movement->save();

    $this->assertDatabaseHas('inventory_items', [
        'id' => $inventoryItem->id,
        'tenant_id' => $tenant->id,
        'name' => 'Alyvos filtras',
    ]);

    expect($inventoryItem->tenant->id)
        ->toBe($tenant->id);

    expect($movement->inventoryItem->id)
        ->toBe($inventoryItem->id);

    expect($movement->performedBy->id)
        ->toBe($employee->id);

    expect($movement->tenant->id)
        ->toBe($tenant->id);

    expect($inventoryItem->movements()->first()->id)
        ->toBe($movement->id);
});

