<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Boards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('boards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unit');
            $table->string('public_title');
            $table->string('announcement_text')->nullable();
            $table->boolean('public');
            $table->boolean('anyone_can_edit')->default(false);
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
}
