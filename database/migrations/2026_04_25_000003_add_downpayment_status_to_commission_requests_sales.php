<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE commission_requests_sales ADD COLUMN downpayment_status VARCHAR(50) NULL AFTER date_of_downpayment");
        } catch (\Exception $e) {
            // Column already exists
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE commission_requests_sales DROP COLUMN downpayment_status");
        } catch (\Exception $e) {}
    }
};
