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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->enum('stnk_status', ['proses', 'ready', 'diserahkan'])->default('proses')->after('status');
            $table->timestamp('stnk_received_at')->nullable()->after('stnk_status');
            $table->timestamp('stnk_handed_over_at')->nullable()->after('stnk_received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['stnk_status', 'stnk_received_at', 'stnk_handed_over_at']);
        });
    }
};
