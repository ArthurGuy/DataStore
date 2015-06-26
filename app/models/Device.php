<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'devices';

    protected $fillable = [
        'name', 'type', 'post_url_on', 'post_url_off', 'location_id', 'option', 'state', 'online'
    ];

    protected $hidden = ['post_url_on', 'post_url_off'];


    public static function dropdown()
    {
        $values = self::all();
        $returnArray = [];
        foreach ($values as $value)
        {
            $returnArray[$value->id] = $value->name;
        }
        return $returnArray;
    }

}
