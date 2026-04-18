<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commission_requests_sales', function (Blueprint $table) {
            $table->decimal('commission_percent', 8, 4)->nullable()->after('net_tcp');
        });
    }

    public function down(): void
    {
        Schema::table('commission_requests_sales', function (Blueprint $table) {
            $table->dropColumn('commission_percent');
        });
    }
};
