<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite does not support MODIFY COLUMN — use Laravel's change() instead
        if (\DB::getDriverName() === 'sqlite') {
            // SQLite: column type changes are handled via table rebuild; skip if already correct
            try {
                Schema::table('commission_requests', function (Blueprint $table) {
                    $table->string('status', 50)->default('Not Yet Released')->change();
                });
            } catch (\Exception $e) {
                // Safe to ignore if already correct
            }
        } else {
            \DB::statement("ALTER TABLE commission_requests MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Not Yet Released'");
        }
    }

    public function down(): void
    {
        if (\DB::getDriverName() === 'sqlite') {
            try {
                Schema::table('commission_requests', function (Blueprint $table) {
                    $table->string('status', 50)->default('NOT YET LIQUIDATED')->change();
                });
            } catch (\Exception $e) {}
        } else {
            \DB::statement("ALTER TABLE commission_requests MODIFY COLUMN status ENUM('LIQUIDATED','NOT YET LIQUIDATED') NOT NULL DEFAULT 'NOT YET LIQUIDATED'");
        }
    }
};
