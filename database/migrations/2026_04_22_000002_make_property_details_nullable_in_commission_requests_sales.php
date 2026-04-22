<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commission_requests_sales', function (Blueprint $table) {
            $table->string('property_details')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('commission_requests_sales', function (Blueprint $table) {
            $table->string('property_details')->nullable(false)->change();
        });
    }
};
