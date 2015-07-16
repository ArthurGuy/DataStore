<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('locations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 100);
            $table->enum('type', ['building', 'room']);
            $table->enum('mode', ['manual', 'auto', 'semi-auto'])->default('manual');
            $table->string('parent_id');
            $table->text('sensors');
            $table->string('postcode', 100);
            $table->string('latitude', 20);
            $table->string('longitude', 20);
            $table->string('country', 2);
            $table->double('temperature');
            $table->double('target_temperature');
            $table->double('away_temperature');
            $table->double('humidity');
            $table->boolean('home');
            $table->timestamp('last_movement')->nullable();
            $table->timestamp('last_detection')->nullable();
            $table->timestamp('last_updated')->nullable();
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
        Schema::drop('locations');
	}

}
