<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerVehicleController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

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
});
