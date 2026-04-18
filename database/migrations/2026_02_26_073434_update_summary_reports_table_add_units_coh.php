<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summary_reports', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('summary_reports', 'report_type')) {
                $table->dropColumn('report_type');
            }
            if (Schema::hasColumn('summary_reports', 'unit')) {
                $table->dropColumn('unit');
            }
            
            // Add new columns
            if (!Schema::hasColumn('summary_reports', 'units')) {
                $table->decimal('units', 15, 2)->default(0)->after('year');
            }
            if (!Schema::hasColumn('summary_reports', 'coh')) {
                $table->decimal('coh', 15, 2)->default(0)->after('gross_sales');
            }
            
            // Ensure month and year columns exist
            if (!Schema::hasColumn('summary_reports', 'month')) {
                $table->integer('month')->after('id');
            }
            if (!Schema::hasColumn('summary_reports', 'year')) {
                $table->integer('year')->after('month');
            }
        });
    }

    public function down(): void
    {
        Schema::table('summary_reports', function (Blueprint $table) {
            $table->dropColumn(['units', 'coh']);
        });
    }
};
