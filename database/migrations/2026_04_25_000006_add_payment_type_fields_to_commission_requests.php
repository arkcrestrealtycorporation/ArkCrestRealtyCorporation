<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $cols = [
            'payment_type'            => "VARCHAR(50) NULL",
            'value_of_payment_terms'  => "DECIMAL(15,2) NULL",
        ];
        foreach ($cols as $col => $type) {
            try { DB::statement("ALTER TABLE commission_requests ADD COLUMN {$col} {$type}"); }
            catch (\Exception $e) {}
        }
    }

    public function down(): void {}
};
