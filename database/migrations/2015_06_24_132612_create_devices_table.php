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
            $table->enum('type', ['heater', 'fan', 'filter', 'light']);
            $table->string('post_url_on', 255);
            $table->string('post_url_off', 255);
            $table->string('post_update_url', 255);
            $table->enum('connection_type', ['spark', 'imp']);
            $table->integer('location_id');
            $table->enum('state_type', ['binary', 'integer', 'light']);
            $table->string('state', 100);
            $table->boolean('on');
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
