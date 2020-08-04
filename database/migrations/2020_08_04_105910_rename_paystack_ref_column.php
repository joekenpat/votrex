<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePaystackRefColumn extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('votes', function (Blueprint $table) {
      $table->string('gateway')->after('user_id');
      $table->renameColumn('paystack_ref', 'transaction_ref');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('votes', function (Blueprint $table) {
      $table->dropColumn('gateway');
      $table->renameColumn('transaction_ref', 'paystack_ref');
    });
  }
}
