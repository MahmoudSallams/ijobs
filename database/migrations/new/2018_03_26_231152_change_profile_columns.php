<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProfileColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('profiles', function (Blueprint $table) 
    	{
    		$table->integer('age')->nullable(false)->default(0)->change();
    		$table->integer('mobile_verify_status')->nullable(false)->default(0)->change();
    		$table->integer('region_id')->nullable(false)->default(0)->change();
    		$table->integer('country_id')->nullable(false)->default(0)->change();
    		$table->integer('city_id')->nullable(false)->default(0)->change();
    		$table->integer('status')->nullable(false)->default(0)->change();
    		
    		//$table->timestamp('created_at')->useCurrent()->change();
    		//$table->timestamp('updated_at')->useCurrent()->change();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
