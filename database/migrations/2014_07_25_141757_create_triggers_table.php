<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('triggers', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('streamId');
            $table->string('name');

            $table->string('check_field');
            $table->string('check_operator');
            $table->string('check_value');

            $table->string('filter_value');
            $table->string('filter_field');

            $table->string('action');
            $table->longText('action_details');

            $table->dateTime('last_trigger');
            $table->boolean('trigger_matched');

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
		Schema::drop('triggers');
	}

}
