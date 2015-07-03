<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'locations';

    protected $fillable = [
        'name', 'postcode', 'country', 'type'
    ];

    public function getDates()
    {
        return ['created_at', 'updated_at', 'last_updated', 'last_movement'];
    }

    protected $appends = ['condition', 'hasWarning', 'heater', 'cooler', 'fan'];

    protected $with = ['devices', 'rooms'];

    protected $hidden = [];

    protected $casts = [
        'sensors' => 'array',
    ];


    public function devices()
    {
        return $this->hasMany('App\Models\Device');
    }

    public function rooms()
    {
        return $this->hasMany('App\Models\Location', 'parent_id');
    }


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


    public function getConditionAttribute()
    {
        $duePoint = \App\Data\Weather\Helper::calculateDuePoint($this->temperature, $this->humidity);
        return \App\Data\Weather\Helper::weatherCondition($duePoint, $this->temperature);
    }

    public function getHumidityAttribute()
    {
        return round($this->attributes['humidity']);
    }

    public function getHasWarningAttribute()
    {
        return $this->last_updated->lt(\Carbon\Carbon::now()->subHours(2));
    }

    public function getHeaterAttribute()
    {
        return $this->devices()->where('type', 'heater')->first();
    }

    public function getCoolerAttribute()
    {
        return $this->devices()->where('type', 'cooler')->first();
    }

    public function getFanAttribute()
    {
        return $this->devices()->where('type', 'fan')->first();
    }

    public function deviceOn($deviceType) {
        $device = $this->device($deviceType);
        return ($device && $device->state);
    }
}
