<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tripping_schedules', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['agent_name', 'agent_contact', 'client_contact', 'project_name', 'property_type', 'notes']);
        });

        Schema::table('tripping_schedules', function (Blueprint $table) {
            // New columns
            $table->string('agent_emp_id')->nullable()->after('id');
            $table->string('client_email')->nullable()->after('client_name');
            $table->string('client_phone')->nullable()->after('client_email');
            $table->string('property_name')->nullable()->after('client_phone');
            $table->string('company_name')->nullable()->after('property_name');
            $table->string('tripping_type')->nullable()->after('tripping_time'); // Actual / Online / custom
        });
    }

    public function down(): void
    {
        Schema::table('tripping_schedules', function (Blueprint $table) {
            $table->dropColumn(['agent_emp_id', 'client_email', 'client_phone', 'property_name', 'company_name', 'tripping_type']);
        });

        Schema::table('tripping_schedules', function (Blueprint $table) {
            $table->string('agent_name')->nullable();
            $table->string('agent_contact')->nullable();
            $table->string('client_contact')->nullable();
            $table->string('project_name')->nullable();
            $table->string('property_type')->nullable();
            $table->text('notes')->nullable();
        });
    }
};
