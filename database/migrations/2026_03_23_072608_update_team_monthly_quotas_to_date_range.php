<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('team_monthly_quotas', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropUnique(['team_id', 'month']);
            $table->dropColumn('month');
            $table->date('date_from')->after('team_id');
            $table->date('date_to')->after('date_from');
            $table->foreign('team_id')->references('id')->on('sales_teams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('team_monthly_quotas', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn(['date_from', 'date_to']);
            $table->string('month', 7)->after('team_id');
            $table->unique(['team_id', 'month']);
            $table->foreign('team_id')->references('id')->on('sales_teams')->onDelete('cascade');
        });
    }
};
