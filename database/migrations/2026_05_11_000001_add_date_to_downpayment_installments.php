<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('downpayment_installments') && !Schema::hasColumn('downpayment_installments', 'paid_date')) {
            Schema::table('downpayment_installments', function (Blueprint $table) {
                $table->date('paid_date')->nullable()->after('paid_at');
            });
        }
        // Add spot downpayment date to commission_requests_sales
        try {
            \DB::statement("ALTER TABLE commission_requests_sales ADD COLUMN downpayment_date DATE NULL");
        } catch (\Exception $e) {}
    }
    public function down(): void {}
};
