<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableJobsTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('job_transaction', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('job_id');
    		$table->integer('profile_id');
    		$table->integer('status');
    		$table->timestamp('created_at')->useCurrent();
    		$table->timestamp('updated_at')->useCurrent();
    		$table->softDeletes();
    		
    		$table->unique(array('job_id', 'profile_id'));
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::drop('job_transaction');
    }
}
