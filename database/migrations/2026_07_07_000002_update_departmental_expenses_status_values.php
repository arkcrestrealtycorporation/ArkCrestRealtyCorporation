<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Statuses for the All Expenses table are now:
        // FOR REQUEST, NOT LIQUIDATED, LIQUIDATED, REJECTED.
        // Normalize any existing rows using the old wording.
        DB::table('departmental_expenses')
            ->where('status', 'NOT YET LIQUIDATED')
            ->update(['status' => 'NOT LIQUIDATED']);

        Schema::table('departmental_expenses', function (Blueprint $table) {
            $table->string('status')->default('FOR REQUEST')->change();
        });
    }

    public function down(): void
    {
        DB::table('departmental_expenses')
            ->where('status', 'NOT LIQUIDATED')
            ->update(['status' => 'NOT YET LIQUIDATED']);

        Schema::table('departmental_expenses', function (Blueprint $table) {
            $table->string('status')->default('NOT YET LIQUIDATED')->change();
        });
    }
};
