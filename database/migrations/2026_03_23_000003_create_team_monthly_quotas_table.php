<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('team_monthly_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('sales_teams')->onDelete('cascade');
            $table->string('month', 7); // YYYY-MM
            $table->decimal('quota_amount', 15, 2);
            $table->timestamps();

            $table->unique(['team_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_monthly_quotas');
    }
};
