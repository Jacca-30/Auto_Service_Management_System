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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('repair_order_id')
                ->constrained('repair_orders')
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->unsignedInteger('version')->default(1);

            $table->enum('status', [
                'draft',
                'sent',
                'approved',
                'rejected',
                'expired',
            ])->default('draft');

            $table->decimal('total_amount', 10, 2)->default(0);

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_current')->default(true);

            $table->string('approval_token_hash')->nullable()->unique();

            $table->timestamps();

            $table->unique(['repair_order_id', 'version']);

            $table->index(['repair_order_id', 'is_current']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
