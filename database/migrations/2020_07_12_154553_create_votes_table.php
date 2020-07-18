<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('votes', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('email');
      $table->string('quantity');
      $table->string('amount');
      $table->enum('status', ['valid', 'invalid'])->default('invalid');
      $table->uuid('contest_id');
      $table->uuid('user_id');
      $table->string('paystack_ref');
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
    Schema::dropIfExists('votes');
  }
}
