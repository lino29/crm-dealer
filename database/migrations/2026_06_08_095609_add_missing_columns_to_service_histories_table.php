<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_histories', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('vehicle_id')->constrained('customers', 'customer_id')->nullOnDelete();
            $table->text('complaint')->nullable()->after('mileage');
            $table->text('service_action')->nullable()->after('complaint');
            $table->text('service_note')->nullable()->after('service_action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_histories', function (Blueprint $table) {
            //
        });
    }
};
