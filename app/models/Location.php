<?php


class Location extends Eloquent {

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
        return ['created_at', 'updated_at', 'last_updated'];
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

    public function rooms() {
        return self::where('type', 'room')->where('parent_id', $this->id)->get();
    }

    public function getConditionAttribute()
    {
        $duePoint = \Data\Weather\Helper::calculateDuePoint($this->temperature, $this->humidity);
        return \Data\Weather\Helper::weatherCondition($duePoint);
    }

    public function devices() {
        return \Device::where('location_id', $this->id)->get();
    }

    public function device($deviceType) {
        return \Device::where('location_id', $this->id)->where('type', $deviceType)->first();
    }

    public function deviceOn($deviceType) {
        $device = $this->device($deviceType);
        return ($device && $device->state);
    }
}
