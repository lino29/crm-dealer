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
        Schema::table('member_cards', function (Blueprint $table) {
            $table->foreignId('dealer_id')->nullable()->after('customer_id')->constrained('dealers', 'dealer_id')->nullOnDelete();
            $table->string('member_code_base')->nullable()->after('member_code');
            $table->integer('duplicate_sequence')->default(0)->after('member_code_base');
            $table->text('qr_payload')->nullable()->after('qr_token');
            $table->date('issued_date')->nullable()->after('qr_payload');
            $table->date('expired_date')->nullable()->after('issued_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_cards', function (Blueprint $table) {
            //
        });
    }
};
