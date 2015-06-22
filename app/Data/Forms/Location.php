<?php namespace Data\Forms;

class Location extends FormValidator
{

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name'     => 'required|unique:locations,name,{id}',
        'type'     => 'required',
        'postcode' => 'required',
        'country'  => 'required|max:2|min:2',
    ];

} 