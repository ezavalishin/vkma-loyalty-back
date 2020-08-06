<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('approver_id')->nullable();
            $table->foreign('approver_id')->on('users')->references('id')->onDelete('cascade');

            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')->on('cards')->references('id')->onDelete('cascade');

            $table->timestamp('approved_at')->nullable();

            $table->string('hash');

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
        Schema::dropIfExists('checkins');
    }
}
