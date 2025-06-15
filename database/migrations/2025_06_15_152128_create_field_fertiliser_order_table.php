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
        Schema::create('field_fertiliser_order', function (Blueprint $table) {
            $table->id(); // Optional, but common in Laravel
            $table->unsignedBigInteger('field_id'); // Foreign key to field table
            $table->unsignedBigInteger('fertiliser_order_id'); // FK to fertiliser_order table
            $table->integer('qty'); // Quantity assigned to field
            $table->dateTime('creation_date'); // Date of assignment

            // Define foreign key constraints
            // Uncomment these if the corresponding tables exist
            // $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            // $table->foreign('fertiliser_order_id')->references('id')->on('fertiliser_order')->onDelete('cascade');

            // Optional: Add unique constraint if a field can only have one entry per order
            // $table->unique(['field_id', 'fertiliser_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_fertiliser_order');
    }
};
