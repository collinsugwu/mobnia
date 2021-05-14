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
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->boolean('admin')->default(false);
            $table->string('push_id')->nullable();
            $table->string('push_os')->nullable();
            $table->text('api_token')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verify_code')->nullable();
            $table->timestamp('phone_verified')->nullable();
            $table->string('phone_verify_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
