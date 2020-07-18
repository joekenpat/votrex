<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->foreign('sch_id')->references('id')->on('schools')->cascadeOnDelete();
      });
      Schema::table('votes', function (Blueprint $table) {
        $table->foreign('contest_id')->references('id')->on('contests')->cascadeOnDelete();
        $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
      });
      Schema::table('contest_user', function (Blueprint $table) {
        $table->foreign('contest_id')->references('id')->on('contests')->cascadeOnDelete();
        $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->dropForeign('sch_id');
      });
      Schema::table('votes', function (Blueprint $table) {
        $table->dropForeign('contest_id');
        $table->dropForeign('user_id');
      });
      Schema::table('contest_user', function (Blueprint $table) {
        $table->dropForeign('contest_id');
        $table->dropForeign('user_id');
      });
    }
}
