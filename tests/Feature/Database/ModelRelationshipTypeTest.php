<?php

use App\Models\Tenant;
use App\Models\User;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\CustomerVehicle;
use App\Models\RepairOrder;
use App\Models\Diagnostic;
use App\Models\Estimate;
use App\Models\EstimateItem;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('all model relationships have correct types', function () {
    $relationships = [
        Tenant::class => [
            'users' => HasMany::class,
            'customers' => HasMany::class,
        ],
        User::class => [
            'tenant' => BelongsTo::class,
            'assignedRepairOrders' => HasMany::class,
        ],
        Customer::class => [
            'tenant' => BelongsTo::class,
            'customerVehicles' => HasMany::class,
        ],
        Vehicle::class => [
            'customerVehicles' => HasMany::class,
        ],
        CustomerVehicle::class => [
            'customer' => BelongsTo::class,
            'vehicle' => BelongsTo::class,
            'repairOrders' => HasMany::class,
        ],
        RepairOrder::class => [
            'tenant' => BelongsTo::class,
            'customerVehicle' => BelongsTo::class,
            'assignedEmployee' => BelongsTo::class,
            'diagnostics' => HasMany::class,
            'estimates' => HasMany::class,
            'inventoryMovements' => HasMany::class,
        ],
        Diagnostic::class => [
            'repairOrder' => BelongsTo::class,
            'performedBy' => BelongsTo::class,
        ],
        Estimate::class => [
            'repairOrder' => BelongsTo::class,
            'creator' => BelongsTo::class,
            'items' => HasMany::class,
        ],
        EstimateItem::class => [
            'estimate' => BelongsTo::class,
        ],
        InventoryItem::class => [
            'tenant' => BelongsTo::class,
            'movements' => HasMany::class,
        ],
        InventoryMovement::class => [
            'tenant' => BelongsTo::class,
            'inventoryItem' => BelongsTo::class,
            'repairOrder' => BelongsTo::class,
            'performedBy' => BelongsTo::class,
        ],
    ];

    foreach ($relationships as $model => $methods) {
        $instance = new $model();

        foreach ($methods as $method => $expectedType) {
            $relationship = $instance->$method();

            expect($relationship)
                ->toBeInstanceOf($expectedType);
        }
    }
});

