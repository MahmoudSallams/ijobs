<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_transaction', function (Blueprint $table) 
        {
        	$table->increments('id');
        	$table->integer('profile_id')->nullable(false);
        	$table->integer('transaction_type')->nullable(false);
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
        Schema::dropIfExists('profile_transaction');
    }
}
