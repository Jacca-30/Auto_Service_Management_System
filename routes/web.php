<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerVehicleController;
use App\Http\Controllers\PublicRepairRequestController;
use App\Http\Controllers\RepairOrderController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\EstimateResponseController;
use App\Http\Controllers\InventoryController;

Route::get('/', function () {
    return redirect()->route('login');
});

//Samatos perziuros ir atsakymo marsrutai (e-mail)
Route::prefix('estimate')
    ->name('estimates.')
    ->group(function () {
    Route::get('/respond/{token}', [EstimateResponseController::class, 'show'])
        ->name('respond');

    Route::post('/respond/{token}/approve', [EstimateResponseController::class, 'approve'])
        ->name('approve');

    Route::post('/respond/{token}/reject', [EstimateResponseController::class, 'reject'])
        ->name('reject');

        Route::get('/result/{status}', [EstimateResponseController::class, 'result'])
            ->name('result');
});

//Viesa forma
Route::get('/request/{tenant:slug}', function (\App\Models\Tenant $tenant) {
    return view('repair-requests.create', compact('tenant'));
})->name('repair-requests.create');

Route::post('/request/{tenant:slug}', [PublicRepairRequestController::class, 'store'])
    ->name('repair-requests.store')
    ->middleware('throttle:5,1'); // penkios užklausos per minutę

//Guest naudotojai
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

//Autentifikuoti naudotojai
Route::middleware(['auth', 'role:admin,employee'])->group(function () {

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('customers')
        ->name('customers.')
        ->group(function () {

            Route::get('/', [CustomerController::class, 'index'])
                ->name('index');

            Route::get('/create', [CustomerController::class, 'create'])
                ->name('create');

            Route::post('/', [CustomerController::class, 'store'])
                ->name('store');

            Route::get('/{customerId}/vehicles/create', [CustomerVehicleController::class, 'create'])
                ->name('vehicles.create');

            Route::post('/{customerId}/vehicles', [CustomerVehicleController::class, 'store'])
                ->name('vehicles.store');

            Route::get('/{customerId}', [CustomerVehicleController::class, 'show'])
                ->name('show');
        });

    Route::prefix('repair-orders')
        ->name('repair-orders.')
        ->group(function () {
            Route::get('/', [RepairOrderController::class, 'index'])
                ->name('index');

            Route::get('/{id}', [RepairOrderController::class, 'show'])
                ->name('show');

            Route::patch('/{id}/accept', [RepairOrderController::class, 'accept'])
                ->name('accept');

            Route::patch('/{id}/decline', [RepairOrderController::class, 'decline'])
                ->name('decline');

            Route::patch('/{id}/complete', [RepairOrderController::class, 'complete'])
                ->name('complete');

            Route::post('/{repairOrderId}/diagnostics', [DiagnosticController::class, 'store'])
                ->name('diagnostics.store');

            Route::get('/{repairOrderId}/estimates/create', [EstimateController::class, 'create'])
                ->name('estimates.create');

            Route::post('/{repairOrderId}/estimates', [EstimateController::class, 'store'])
                ->name('estimates.store');

            Route::post('/estimates/{estimateId}/send', [EstimateController::class, 'send'])
                ->name('estimates.send');
        });

    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])
            ->name('index');

        Route::get('/create', [InventoryController::class, 'create'])
            ->name('create');

        Route::post('/', [InventoryController::class, 'store'])
            ->name('store');

        Route::post('/{id}/receive', [InventoryController::class, 'receive'])
            ->name('receive');

        Route::post('/{id}/consume', [InventoryController::class, 'consume'])
            ->name('consume');

        Route::get('/{id}', [InventoryController::class, 'show'])
            ->name('show');
    });
});
