<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tripping_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('agent_name');
            $table->string('agent_contact')->nullable();
            $table->string('client_name');
            $table->string('client_contact')->nullable();
            $table->string('project_name');
            $table->date('tripping_date');
            $table->time('tripping_time')->nullable();
            $table->string('property_type')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'done', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tripping_schedules');
    }
};
