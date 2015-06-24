<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name', 100);
            $table->enum('type', ['heater', 'fan', 'filter']);
            $table->string('post_url_on', 255);
            $table->string('post_url_off', 255);
            $table->integer('location_id');
            $table->enum('option', ['binary']);
            $table->string('state', 100);
            $table->boolean('online');
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
		Schema::drop('devices');
	}

}
