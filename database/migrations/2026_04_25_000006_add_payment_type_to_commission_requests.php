<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try { DB::statement("ALTER TABLE commission_requests ADD COLUMN payment_type VARCHAR(50) NULL"); } catch (\Exception $e) {}
        try { DB::statement("ALTER TABLE commission_requests ADD COLUMN value_of_payment_terms DECIMAL(15,2) NULL"); } catch (\Exception $e) {}
    }

    public function down(): void {}
};
