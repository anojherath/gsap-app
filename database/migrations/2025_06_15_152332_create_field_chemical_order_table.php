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
        Schema::create('field_chemical_order', function (Blueprint $table) {
            $table->id(); // Optional primary key
            $table->unsignedBigInteger('field_id'); // Reference to fields
            $table->unsignedBigInteger('chemical_order_id'); // Reference to chemical orders
            $table->integer('qty'); // Quantity applied
            $table->dateTime('creation_date'); // When applied

            // Optional: Add foreign keys if related tables exist
            // $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            // $table->foreign('chemical_order_id')->references('id')->on('chemical_order')->onDelete('cascade');

            // Optional: Composite unique constraint if each field-order pair must be unique
            // $table->unique(['field_id', 'chemical_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_chemical_order');
    }
};
