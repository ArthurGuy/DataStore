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
        'name', 'fields', 'tags'
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

}
