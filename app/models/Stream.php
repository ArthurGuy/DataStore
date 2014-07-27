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
        'name', 'fields', 'current_values', 'filter_field'
    ];

    protected $guarded = array('id');

    public function __construct(array $attributes = array())
    {
        $this->setRawAttributes(['id' => str_random(10)], true);
        parent::__construct($attributes);
    }


    public function getFieldsAttribute($value)
    {
        //trim the returned array as well
        return array_map("trim", explode(',', $value));
    }

    public function setFieldsAttribute($value)
    {
        if (is_array($value))
        {
            $this->attributes['fields'] = implode(",", array_map("trim", $value));
        }
        else
        {
            $this->attributes['fields'] = $value;
        }
    }

    public function getFieldListAttribute()
    {
        throw new \Exception("Field list being called by something");
        $fieldList = [];
        foreach($this->fields as $field)
        {
            $fieldList[] = $field['key'];
        }
        return $fieldList;
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

    /**
     * Get the main filter field for this data stream
     * @TODO: Deal with multiple filter fields
     * @return bool
     */
    public function getFilter()
    {
        foreach ($this->fields as $field)
        {
            if ($field['type'] == 'filter')
            {
                return $field['key'];
            }
        }
        return false;
    }

    public function getDetectedFilters()
    {
        return array_keys($this->current_values);
    }

    public function updateCurrentValues($values)
    {
        $filter = $this->getFilter();
        $currentValues = $this->current_values;
        foreach ($this->fields as $field)
        {
            //Look for each valid data field in the incoming data
            if (($field['type'] == 'data') && isset($values[$field['key']]))
            {
                if (isset($values[$filter]))
                {
                    $currentValues[$values[$filter]][$field['key']] = $values[$field['key']];
                }
                else
                {
                    $currentValues['global'][$field['key']] = $values[$field['key']];
                }
            }
        }
        $this->current_values = $currentValues;
        $this->save();
    }

}
