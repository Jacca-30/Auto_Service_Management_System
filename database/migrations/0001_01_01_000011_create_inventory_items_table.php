<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->enum('type', [
                'part',
                'material',
            ]);

            $table->string('name');
            $table->string('oem_number')->nullable();
            $table->string('ap_number')->nullable();

            $table->string('unit'); // vnt., l, kg ir pan.
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('minimum_quantity', 10, 2)->default(0);
            $table->decimal('purchase_price', 10, 2)->default(0);

            $table->timestamps();

            $table->index(['tenant_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
