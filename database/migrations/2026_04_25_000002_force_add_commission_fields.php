<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Force add columns using raw SQL to bypass hasColumn checks
        $columns = [
            'project_name' => 'VARCHAR(255) NULL',
            'property_details' => 'VARCHAR(255) NULL',
            'client_name' => 'VARCHAR(255) NULL',
            'terms_of_payment' => 'VARCHAR(255) NULL',
            'agent_name' => 'VARCHAR(255) NULL',
            'number_of_units' => 'INT NULL',
            'net_tcp' => 'DECIMAL(15,2) NULL',
            'commission' => 'DECIMAL(15,2) NULL',
            'mode_of_payment' => 'VARCHAR(255) NULL',
            'reservation_date' => 'DATE NULL',
            'price_sqm' => 'DECIMAL(15,2) NULL',
            'lot_area' => 'DECIMAL(15,4) NULL',
            'discount' => 'DECIMAL(15,2) NULL',
            'commission_percent' => 'DECIMAL(8,4) NULL',
            'remarks' => 'TEXT NULL',
        ];

        foreach ($columns as $col => $type) {
            try {
                DB::statement("ALTER TABLE commission_requests ADD COLUMN {$col} {$type}");
            } catch (\Exception $e) {
                // Column already exists, skip
            }
        }
    }

    public function down(): void
    {
        // No rollback
    }
};
