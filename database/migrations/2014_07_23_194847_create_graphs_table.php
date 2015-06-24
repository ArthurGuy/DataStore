<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraphsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('graphs', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('streamId');
            $table->string('name');
            $table->string('field');
            $table->string('time_period');
            $table->string('filter');
            $table->string('filter_field');
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('graphs');
	}

}
