<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIdToFertilizerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('fertiliser_order', function (Blueprint $table) {
            $table->unsignedBigInteger('field_id')->nullable()->after('user_id');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('fertiliser_order', function (Blueprint $table) {
            $table->dropForeign(['field_id']);
            $table->dropColumn('field_id');
        });
    }
}
