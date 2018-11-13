<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProfiles extends Migration
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
    		$table->timestamp('current_work_from')->nullable(true)->after('company_name');
    		$table->timestamp('current_work_to')->nullable(true)->after('current_work_from');
    		$table->text('scientific_qualifications')->after('status');
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
