<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnableSlack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->boolean('push_to_slack')->default(false);
            $table->string('slack_token')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('slack_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->dropColumn('push_to_slack');
            $table->dropColumn('slack_token');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slack_user');
        });
    }
}
