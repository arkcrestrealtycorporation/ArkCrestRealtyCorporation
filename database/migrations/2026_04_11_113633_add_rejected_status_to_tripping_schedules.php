<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support modifying enums, so we use a string column approach
        // The status column is already a string in SQLite, just update the model/controller
        // For MySQL, alter the enum:
        try {
            \DB::statement("ALTER TABLE tripping_schedules MODIFY COLUMN status ENUM('pending','confirmed','done','cancelled','rejected') DEFAULT 'pending'");
        } catch (\Exception $e) {
            // SQLite - no action needed, string column accepts any value
        }
    }

    public function down(): void {}
};
