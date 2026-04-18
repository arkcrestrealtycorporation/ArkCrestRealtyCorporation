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
        Schema::create('summary_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('report_type', ['yearly', 'monthly']);
            $table->integer('year');
            $table->integer('month')->nullable();
            $table->decimal('unit', 15, 2)->default(0);
            $table->decimal('gross_sales', 15, 2)->default(0);
            $table->timestamps();
            
            // Unique constraint: one record per year (yearly) or per year-month (monthly)
            $table->unique(['report_type', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_reports');
    }
};
