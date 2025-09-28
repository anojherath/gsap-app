<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harvest', function (Blueprint $table) {
            // Drop the old foreign key if it exists
            $table->dropForeign(['fertilizer_id']);
            
            // Re-add foreign key pointing to fertiliser_order
            $table->foreign('fertilizer_id')
                  ->references('id')
                  ->on('fertiliser_order')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('harvest', function (Blueprint $table) {
            $table->dropForeign(['fertilizer_id']);
            // Optionally restore old foreign key if needed
        });
    }
};
