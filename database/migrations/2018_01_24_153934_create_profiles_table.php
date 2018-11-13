<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->integer('age');
            $table->integer('mobile');
            $table->integer('other_mobile');
            $table->string('mobile_verify_code');
            $table->integer('mobile_verify_status');
            $table->string('title');
            $table->string('company');
            $table->integer('region_id');
            $table->integer('country_id');
            $table->integer('city_id');
            $table->string('gender');
            $table->text('brief');
            $table->string('photo');
            $table->integer('status');
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
        Schema::drop('profiles');
    }
}
