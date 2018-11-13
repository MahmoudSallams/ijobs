<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_token', function (Blueprint $table) 
        {
        	$table->increments('id');
        	
        	$table->integer('profile_id')->nullable(false)->default(0);
        	$table->text('token');
        	$table->tinyInteger('device_type')->nullable(false)->default(1);
        	$table->boolean('is_active')->nullable(false)->default(true);
        	
        	$table->timestamp('created_at')->nullable(false)->useCurrent();
        	$table->timestamp('updated_at')->nullable(false)->useCurrent();
        	
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
        Schema::dropIfExists('profile_tokens');
    }
}
