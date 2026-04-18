<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales_teams', function (Blueprint $table) {
            $table->string('team_name')->nullable()->after('id');
            $table->string('sales_manager')->nullable()->after('team_name');
        });
    }

    public function down(): void
    {
        Schema::table('sales_teams', function (Blueprint $table) {
            $table->dropColumn(['team_name', 'sales_manager']);
        });
    }
};
