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
        Schema::table('fertiliser_order', function (Blueprint $table) {
            $table->unsignedBigInteger('fertilizer_provider_id')->after('user_id');

            // Uncomment the below lines if you want a foreign key constraint:
            // $table->foreign('fertilizer_provider_id')
            //       ->references('id')->on('users')
            //       ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertiliser_order', function (Blueprint $table) {
            // If you added a foreign key, drop it before dropping the column:
            // $table->dropForeign(['fertilizer_provider_id']);
            
            $table->dropColumn('fertilizer_provider_id');
        });
    }
};
