<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('internet_id');
            $table->string('office')->nullable();
            $table->string('phone')->nullable();
            $table->string('calendar_link')->nullable();
            $table->date('birthday')->nullable();
            $table->dateTime("sign_in")->nullable();
            $table->dateTime("sign_out")->nullable();
            $table->text("message")->nullable();
            $table->boolean("global_admin")->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
