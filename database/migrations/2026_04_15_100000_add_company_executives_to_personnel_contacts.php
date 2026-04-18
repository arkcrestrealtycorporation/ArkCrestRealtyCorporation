<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personnel_contacts', function (Blueprint $table) {
            $table->string('company')->nullable()->after('name');
            $table->string('executives')->nullable()->after('company');
        });
    }

    public function down(): void
    {
        Schema::table('personnel_contacts', function (Blueprint $table) {
            $table->dropColumn(['company', 'executives']);
        });
    }
};
