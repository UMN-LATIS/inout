<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BoardUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_user', function (Blueprint $table) {
            $table->integer('board_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean("is_admin")->default(false);
            $table->datetime("winner")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_user');
    }
}