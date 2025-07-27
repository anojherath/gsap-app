<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('seed_orders', function (Blueprint $table) {
            // You only need ->nullable() and ->change() — don't add ->default(null)
            $table->boolean('farmer_confirmed')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('seed_orders', function (Blueprint $table) {
            $table->boolean('farmer_confirmed')->default(false)->change();
        });
    }
};
