<?php


class Device extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'devices';

    protected $fillable = [
        'name', 'type', 'post_url_on', 'post_url_off', 'location_id', 'option', 'state', 'online'
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
