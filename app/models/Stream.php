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
        'name', 'fields', 'current_values', 'filter_field', 'filter_field_names'
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

    public function getCurrentValuesAttribute($value)
    {
        $value = json_decode($value, true);
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


    public function getDetectedFilters()
    {
        return array_keys($this->current_values);
    }

    public function updateCurrentValues($values)
    {
        $currentValues = $this->current_values;
        foreach ($this->fields as $field)
        {
            //Look for each valid data field in the incoming data
            if (isset($values[$field]))
            {
                if (isset($values[$this->filter_field]))
                {
                    $currentValues[$values[$this->filter_field]][$field] = $values[$field];
                }
                else
                {
                    $currentValues['global'][$field] = $values[$field];
                }
            }
        }
        $this->current_values = $currentValues;
        $this->save();
    }

    public function lookupFilterName($filterField)
    {
        $names = explode(PHP_EOL, $this->filter_field_names);
        foreach ($names as $nameRow)
        {
            $nameParts = explode(':', $nameRow);
            if ($nameParts[0] == $filterField)
            {
                return $nameParts[1];
            }
        }
        return $filterField;
    }
}
