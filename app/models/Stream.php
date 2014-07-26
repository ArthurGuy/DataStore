<?php


class Stream extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'streams';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'name', 'fields', 'tags', 'current_values'
    ];

    protected $guarded = array('id');

    public function __construct(array $attributes = array())
    {
        //$this->attributes['id'] = str_random(10);
        $this->setRawAttributes(['id' => str_random(10)], true);
        parent::__construct($attributes);
    }


    public function getFieldsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getFieldListAttribute()
    {
        $fieldList = [];
        foreach($this->fields as $field)
        {
            $fieldList[] = $field['key'];
        }
        return $fieldList;
    }

    public function getTagsAttribute($value)
    {
        return explode(',',$value);
    }

    public function setTagsAttribute($value)
    {
        if (is_array($value))
        {
            $this->attributes['tags'] = implode(',',$value);
        }
        else
        {
            $this->attributes['tags'] = $value;
        }
    }

    public function getCurrentValuesAttribute($value)
    {
        $value = json_decode($value, true);;
        if (empty($value))
        {
            return [];
        }
        else
        {
            return $value;
        }
    }

    public function setCurrentValuesAttribute($value)
    {
        if (!is_array($value))
        {
            $value = [];
        }
        $this->attributes['current_values'] = json_encode($value);
    }

    public function updateCurrentValues($values)
    {
        $currentValues = $this->current_values;
        foreach ($this->fields as $field)
        {
            //Look for each valid data field in the incoming data
            if (($field['type'] == 'data') && isset($values[$field['key']]))
            {
                $currentValues[$field['key']] = $values[$field['key']];
            }
        }
        $this->current_values = $currentValues;
        $this->save();
    }

}
