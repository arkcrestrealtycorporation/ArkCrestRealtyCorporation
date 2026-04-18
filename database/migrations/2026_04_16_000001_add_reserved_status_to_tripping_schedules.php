<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        try {
            \DB::statement("ALTER TABLE tripping_schedules MODIFY COLUMN status ENUM('pending','confirmed','done','cancelled','rejected','reserved') DEFAULT 'pending'");
        } catch (\Exception $e) {
            // SQLite - no action needed
        }
    }

    public function down(): void {}
};
