<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reserved_clients', function (Blueprint $table) {
            $table->string('address')->nullable()->after('client_phone_code');
            $table->string('source')->nullable()->after('address'); // e.g. "Site Visit", "Walk-in"
        });
    }

    public function down(): void
    {
        Schema::table('reserved_clients', function (Blueprint $table) {
            $table->dropColumn(['address', 'source']);
        });
    }
};
