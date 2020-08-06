<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('color_id');
            $table->foreign('color_id')->on('colors')->references('id')->onDelete('cascade');

            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->on('groups')->references('id')->onDelete('cascade');

            $table->unsignedTinyInteger('checkins_count');
            $table->string('description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
