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
        Schema::create('harvest', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('paddy_id'); // References a paddy variety or record
            $table->unsignedBigInteger('field_id'); // Field where harvest occurred
            $table->integer('qty'); // Quantity harvested
            $table->dateTime('creation_date'); // Date of harvest
            $table->boolean('buyer_confirmed')->default(false); // Buyer confirmation status

            // Optional: Add foreign key constraints
            // $table->foreign('paddy_id')->references('id')->on('paddies')->onDelete('cascade');
            // $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest');
    }
};
