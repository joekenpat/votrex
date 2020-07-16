<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('contests', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('title');
      $table->string('image')->nullable()->default(null);
      $table->decimal('reg_fee', 18, 2)->default(0)->nullable();
      $table->decimal('vote_fee', 18, 2)->default(0)->nullable();
      $table->timestamp('started_at', 6)->nullable()->default(null);
      $table->timestamp('ended_at', 6)->nullable()->default(null);
      $table->timestamp('created_at', 6)->nullable()->default(null);
      $table->timestamp('updated_at', 6)->nullable()->default(null);
      $table->timestamp('deleted_at', 6)->nullable()->default(null);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('contests');
  }
}
