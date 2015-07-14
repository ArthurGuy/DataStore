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
        'name', 'type', 'post_url_on', 'post_url_off', 'location_id', 'state_type', 'state', 'on', 'online'
    ];

    protected $hidden = ['post_url_on', 'post_url_off', 'post_update_url', 'created_at', 'updated_at'];

    protected $casts = [
        'on' => 'boolean',
    ];


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


    public function getStateAttribute($originalState)
    {
        if ($this->attributes['state_type'] == 'binary') {
            return (bool)$originalState;
        } elseif ($this->attributes['state_type'] == 'integer') {
            return (int)$originalState;
        }
        return $originalState;
    }

}
