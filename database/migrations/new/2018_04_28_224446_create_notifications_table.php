<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) 
        {
        	$table->increments('id');
        	
        	$table->integer('profile_id')->nullable(false);
        	$table->integer('token_id')->nullable(false);
        	$table->string("body");
        	$table->integer('action_type')->default(0);
        	$table->string("action_value");
        	$table->integer("success")->default(0);
        	$table->string('sending_result');
        	$table->boolean('is_new')->default(true);
        	
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
        Schema::dropIfExists('notifications');
    }
}
