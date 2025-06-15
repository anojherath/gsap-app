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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('image_url')->nullable();
            $table->string('company_name')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nic');
            $table->string('mobile_number');
            $table->string('password');
            $table->text('address');
            $table->foreignId('user_type_id')->constrained('user_types');
            $table->dateTime('creation_date');
            $table->boolean('disabled')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
