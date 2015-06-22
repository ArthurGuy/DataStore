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
        'postcode' => 'required_if:type,building',
        'country'  => 'required_if:type,building|max:2|min:2',
    ];

} 