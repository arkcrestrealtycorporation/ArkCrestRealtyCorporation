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
        Schema::create('commission_requests_sales', function (Blueprint $table) {
            $table->id();
            $table->date('date_requested');
            $table->string('project_name');
            $table->string('property_details');
            $table->string('client_name');
            $table->string('terms_of_payment');
            $table->string('agent_name');
            $table->integer('number_of_units');
            $table->decimal('net_tcp', 15, 2)->nullable();
            $table->decimal('commission', 15, 2)->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->date('date_released')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_requests_sales');
    }
};
