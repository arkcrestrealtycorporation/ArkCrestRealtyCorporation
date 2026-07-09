<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departmental_expenses', function (Blueprint $table) {
            // Full snapshot of everything filled in on the printed Budget
            // Request / Liquidation form (name, dates, liquidation line
            // items, signatures, etc.) so the exact form can be viewed and
            // printed again later from the All Expenses table, even though
            // the summary columns on this table stay blank until Finance
            // updates them.
            $table->json('form_data')->nullable()->after('date_of_amount_returned');
        });
    }

    public function down(): void
    {
        Schema::table('departmental_expenses', function (Blueprint $table) {
            $table->dropColumn('form_data');
        });
    }
};
