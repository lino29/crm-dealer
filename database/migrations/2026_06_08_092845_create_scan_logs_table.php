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
        Schema::create('scan_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('card_id')->nullable()->constrained('member_cards', 'card_id')->nullOnDelete();
            $table->timestamp('scanned_at')->useCurrent();
            $table->enum('status', ['success', 'failed', 'invalid']);
            $table->text('device_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_logs');
    }
};
