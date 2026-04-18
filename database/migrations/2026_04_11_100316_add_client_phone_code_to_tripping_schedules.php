<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tripping_schedules', function (Blueprint $table) {
            $table->string('client_phone_code')->nullable()->default('+63')->after('client_phone');
        });
    }

    public function down(): void
    {
        Schema::table('tripping_schedules', function (Blueprint $table) {
            $table->dropColumn('client_phone_code');
        });
    }
};
