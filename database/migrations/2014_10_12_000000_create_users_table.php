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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('umndid')->nullable();
            $table->string('office')->nullable();
            $table->string('phone')->nullable();
            $table->string('calendar_link')->nullable();
            $table->date('birthday')->nullable();
            $table->dateTime("sign_in")->nullable();
            $table->dateTime("sign_out")->nullable();
            $table->text("message")->nullable();
            $table->boolean("global_admin")->default(false);
            $table->boolean("guest_user")->default(false);
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
