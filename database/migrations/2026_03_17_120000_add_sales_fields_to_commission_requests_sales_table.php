<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commission_requests_sales', function (Blueprint $table) {
            $table->decimal('tcp', 15, 2)->nullable()->after('lot_area');
            $table->date('date_of_downpayment')->nullable()->after('reservation_date');
        });
    }

    public function down(): void
    {
        Schema::table('commission_requests_sales', function (Blueprint $table) {
            $table->dropColumn(['tcp', 'date_of_downpayment']);
        });
    }
};
