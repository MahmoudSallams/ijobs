<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_experience', function (Blueprint $table) 
        {
        	$table->increments('id');
        	$table->integer('profile_id')->nullable(false);
        	$table->string('title', 100)->nullable(false);
        	$table->string('company_name', 100)->nullable(false);
        	$table->timestamp('from')->nullable(false)->useCurrent();
        	$table->timestamp('to')->nullable(false)->useCurrent();
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
        Schema::dropIfExists('work_experience');
    }
}
