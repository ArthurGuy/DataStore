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
            $table->string('parent_id');
            $table->string('postcode', 100);
            $table->string('country', 2);
            $table->double('temperature');
            $table->double('target_temperature');
            $table->double('away_temperature');
            $table->double('humidity');
            $table->boolean('home');
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
