<?php

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
            $table->increments('id');// PK
            $table->string('name'); // varchar
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->enum('role', ['administrator', 'editor', 'visitor'])->default('visitor'); // ENUM('administrator', 'editor') DEFAULT 'editor'
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
        Schema::drop('users');
    }
}
