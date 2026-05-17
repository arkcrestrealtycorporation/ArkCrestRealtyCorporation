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
        // Drop the old unique constraint if it still exists (may have been removed by a prior migration)
        try {
            Schema::table('summary_reports', function (Blueprint $table) {
                $table->dropUnique('summary_reports_report_type_year_month_unique');
            });
        } catch (\Exception $e) {
            // Already dropped — safe to ignore
        }

        // Add new unique constraint for year and month only (if not already present)
        try {
            Schema::table('summary_reports', function (Blueprint $table) {
                $table->unique(['year', 'month'], 'summary_reports_year_month_unique');
            });
        } catch (\Exception $e) {
            // Already exists — safe to ignore
        }
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
