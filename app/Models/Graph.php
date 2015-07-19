<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graph extends Model {

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
