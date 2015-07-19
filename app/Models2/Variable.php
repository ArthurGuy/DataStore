<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'variables';

    protected $fillable = [
        'name', 'value', 'type'
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
}
