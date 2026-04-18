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
        Schema::table('summary_reports', function (Blueprint $table) {
            // Drop the old unique constraint that references report_type
            $table->dropUnique('summary_reports_report_type_year_month_unique');
            
            // Add new unique constraint for year and month only
            $table->unique(['year', 'month'], 'summary_reports_year_month_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('summary_reports', function (Blueprint $table) {
            $table->dropUnique('summary_reports_year_month_unique');
        });
    }
};
