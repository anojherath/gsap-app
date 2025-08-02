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
        Schema::create('fertiliser_order', function (Blueprint $table) {
            $table->id(); // id (primary key)
            $table->unsignedBigInteger('user_id'); // Foreign key to users table (assuming)
            $table->string('type'); // Type of fertiliser
            $table->integer('qty'); // Quantity
            $table->dateTime('creation_date'); // Explicit creation date
            $table->boolean('farmer_confirmed')->nullable()->default(null);
            $table->timestamps();

            // Optional: If you want Laravel's default timestamps too
            // $table->timestamps(); 
            
            // If there's a users table, you can add a foreign key constraint:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertiliser_order');
    }
};
