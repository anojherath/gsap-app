<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fertiliser_order', function (Blueprint $table) {
            $table->boolean('farmer_confirmed')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('fertiliser_order', function (Blueprint $table) {
            $table->boolean('farmer_confirmed')->default(false)->change();
        });
    }
};
