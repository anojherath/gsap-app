<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harvest', function (Blueprint $table) {
            // Only add foreign key (column already exists)
            $table->foreign('fertilizer_id')
                  ->references('id')
                  ->on('fertilizers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('harvest', function (Blueprint $table) {
            $table->dropForeign(['fertilizer_id']);
        });
    }
};
