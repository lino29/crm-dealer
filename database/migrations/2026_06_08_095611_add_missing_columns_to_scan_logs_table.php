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
        Schema::table('scan_logs', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('card_id')->constrained('customers', 'customer_id')->nullOnDelete();
            $table->string('qr_token_scanned')->nullable()->after('customer_id');
            $table->foreignId('scanned_by')->nullable()->after('status')->constrained('users', 'user_id')->nullOnDelete();
            $table->string('ip_address')->nullable()->after('device_info');
            $table->text('note')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scan_logs', function (Blueprint $table) {
            //
        });
    }
};
