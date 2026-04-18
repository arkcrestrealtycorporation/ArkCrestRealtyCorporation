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
        Schema::create('commission_requests', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->unique();
            $table->string('requestor_name');
            $table->string('department');
            $table->string('category');
            $table->date('date_requested');
            $table->decimal('requested_amount', 15, 2);
            $table->enum('status', ['LIQUIDATED', 'NOT YET LIQUIDATED'])->default('NOT YET LIQUIDATED');
            $table->date('date_released')->nullable();
            $table->decimal('total_expenses', 15, 2)->nullable();
            $table->decimal('amount_returned', 15, 2)->nullable();
            $table->date('date_of_amount_returned')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_requests');
    }
};
