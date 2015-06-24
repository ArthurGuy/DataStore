<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APIResponse extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'api_response';

    protected $fillable = [
        'name', 'response',
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
