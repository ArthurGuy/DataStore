<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('streams', function(Blueprint $table)
		{
            $table->string('id', 20);
            $table->primary('id');
            $table->string('name');
            $table->longText('fields');
            $table->string('filter_field', 50);
            $table->text('filter_field_names');
            $table->longText('current_values');
            $table->integer('response_id', 10);
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
        Schema::dropIfExists('streams');
	}

}
