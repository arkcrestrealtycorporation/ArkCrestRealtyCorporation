<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->time('reminder_time')->nullable()->after('note_date');
            $table->boolean('reminder_sent')->default(false)->after('reminder_time');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['reminder_time', 'reminder_sent']);
        });
    }
};
