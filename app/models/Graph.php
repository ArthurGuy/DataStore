<?php


class Graph extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'graphs';

    protected $fillable = [
        'name', 'streamId', 'field', 'time_period', 'filter', 'filter_field'
    ];


}
