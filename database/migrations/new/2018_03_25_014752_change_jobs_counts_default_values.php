<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJobsCountsDefaultValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('jobs', function (Blueprint $table) {
    		$table->integer('applied_count')->default(0)->change();
    		$table->integer('forwarded_count')->default(0)->change();
    		$table->integer('shared_count')->default(0)->change();
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
