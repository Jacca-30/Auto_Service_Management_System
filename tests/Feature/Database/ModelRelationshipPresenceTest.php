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

test('all models have their required relationships', function () {
    $relationships = [
        Tenant::class => ['users', 'customers'],
        User::class => ['tenant', 'assignedRepairOrders'],
        Customer::class => ['tenant', 'customerVehicles'],
        Vehicle::class => ['customerVehicles'],
        CustomerVehicle::class => ['customer', 'vehicle', 'repairOrders'],
        RepairOrder::class => [
            'tenant',
            'customerVehicle',
            'assignedEmployee',
            'diagnostics',
            'estimates',
            'inventoryMovements',
        ],
        Diagnostic::class => ['repairOrder', 'performedBy'],
        Estimate::class => ['repairOrder', 'creator', 'items'],
        EstimateItem::class => ['estimate'],
        InventoryItem::class => ['tenant', 'movements'],
        InventoryMovement::class => [
            'tenant',
            'inventoryItem',
            'repairOrder',
            'performedBy',
        ],
    ];

    foreach ($relationships as $model => $methods) {
        $instance = new $model();

        foreach ($methods as $method) {
            expect(method_exists($instance, $method))
                ->toBeTrue("Missing relationship: {$model}::{$method}()");
        }
    }
});
