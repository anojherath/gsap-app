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
        Schema::table('seed_orders', function (Blueprint $table) {
            $table->foreignId('seed_provider_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seed_orders', function (Blueprint $table) {
            $table->dropForeign(['seed_provider_id']);
            $table->dropColumn('seed_provider_id');
        });
    }
};
