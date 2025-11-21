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
            $table->id();
            $table->string('name', 100)->nullable(false); //NOT NULL
            $table->string('email', 255)->unique();       //UNIQUE
            $table->string('password', 255)->nullable(false);
        });

        //住所
        $table->string('postal_code', 10)->nullable();
        $table->string('address', 255)->nullable();
        $table->string('address_detail', 255)->nullable();
        $table->string('profile_image', 255)->nullable();
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
