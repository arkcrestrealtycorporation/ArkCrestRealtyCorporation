<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personnel_contacts', function (Blueprint $table) {
            $table->dropColumn(['company', 'executives', 'position']);
            $table->string('label')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('personnel_contacts', function (Blueprint $table) {
            $table->dropColumn('label');
            $table->string('company')->nullable()->after('name');
            $table->string('executives')->nullable()->after('company');
            $table->string('position')->nullable()->after('executives');
        });
    }
};
