<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->foreignId('customer_vehicle_id')
                ->constrained('customer_vehicles')
                ->restrictOnDelete();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', [
                'pending', // laukiama darbuotojo sprendimo (accepted arba declined)
                'accepted', // darbuotojas prieme uzklausa (diagnosing)
                'declined', // darbuotojas atmete uzklausa
                'diagnosing', // atliekama diagnostika (awaiting_approval)
                'awaiting_approval', // laukiama samatos patvirtinimo (in_repair arba rejected)
                'in_repair', // vykdomas remontas (completed)
                'completed', // remontas baigtas
            ])->default('pending');

            $table->text('customer_complaint');

            $table->text('internal_notes')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_orders');
    }
};
