<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Device|null lighting
 * @property integer     building_id
 * @property integer     user_id
 * @property string      name
 * @property bool        has_warning
 * @property double      target_temperature
 * @property double      away_temperature
 * @property double      temperature
 */
class Location extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'locations';

    protected $fillable = [
        'name', 'postcode', 'country', 'type', 'target_temperature', 'away_temperature', 'mode'
    ];

    public function getDates()
    {
        return ['created_at', 'updated_at', 'last_updated', 'last_movement', 'last_detection'];
    }

    protected $appends = ['condition', 'hasWarning', 'heater', 'cooler', 'fan', 'lighting', 'occupied'];

    protected $with = ['devices', 'rooms'];

    protected $hidden = [];

    protected $casts = [
        'sensors' => 'array',
        'home'    => 'bool',
    ];


    public function devices()
    {
        return $this->hasMany(\App\Models\Device::class);
    }

    public function rooms()
    {
        return $this->hasMany(\App\Models\Location::class, 'parent_id');
    }

    public function building()
    {
        return $this->belongsTo(\App\Models\Location::class, 'parent_id');
    }

    /**
     * Scope a query to only include rooms
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRoomsOnly($query)
    {
        return $query->where('type', 'room');
    }

    public function getOccupiedAttribute()
    {
        return $this->occupied();
    }

    /**
     * Is the current room occupied?
     *
     * @return bool
     */
    public function occupied() {
        //if no one is home then its definitely not occupied
        if (!$this->buildingOccupied()) {
            return false;
        }
        return $this->recentMovement();
    }

    /**
     * Is the building occupied, this will be the home value from the parent building
     *
     * @return mixed
     */
    public function buildingOccupied()
    {
        if ($this->type == 'room') {
            return $this->building()->first()->home;
        }
        return $this->home;
    }

    /**
     * Has this location seen movement in the last 30 minutes?
     *
     * @return bool
     */
    public function recentMovement()
    {
        return $this->last_movement->gt(Carbon::now()->subMinutes(30));
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
        return $this->last_updated->lt(\Carbon\Carbon::now()->subMinutes(30));
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

    public function getLightingAttribute()
    {
        return $this->devices()->where('type', 'light')->first();
    }

    public function deviceOn($deviceType) {
        $device = $this->device($deviceType);
        return ($device && $device->value);
    }
}
