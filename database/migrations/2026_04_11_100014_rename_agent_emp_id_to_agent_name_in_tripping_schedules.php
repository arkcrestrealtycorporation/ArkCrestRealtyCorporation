<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tripping_schedules', function (Blueprint $table) {
            $table->renameColumn('agent_emp_id', 'agent_name');
        });
    }

    public function down(): void
    {
        Schema::table('tripping_schedules', function (Blueprint $table) {
            $table->renameColumn('agent_name', 'agent_emp_id');
        });
    }
};
