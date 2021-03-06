<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
          $table->uuid('id')->primary();
          $table->string('first_name');
          $table->string('last_name');
          $table->string('middle_name')->nullable()->default(null);
          $table->string('phone')->nullable()->default(null);
          $table->string('state')->nullable()->default(null);
          $table->unsignedBigInteger('sch_id')->nullable()->default(null);
          $table->enum('sch_level',['100','200','300','400','500','600','ND1','ND2','HND1','HND2'])->nullable()->default(null);
          $table->string('sch_faculty')->nullable()->default(null);
          $table->integer('age')->nullable()->default(null);
          $table->enum('role', ['user', 'admin'])->default('user');
          $table->enum('gender', ['male', 'female']);
          $table->string('email')->unique();
          $table->timestamp('email_verified_at')->nullable();
          $table->string('password');
          $table->string('media')->nullable()->default('[]');
          $table->string('avatar')->nullable()->default(null);
          $table->ipAddress('last_ip');
          $table->rememberToken();
          $table->string('bio',5000)->nullable()->default(null);
          $table->timestamp('last_login', 6)->nullable()->default(null);
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
        Schema::dropIfExists('users');
    }
}
