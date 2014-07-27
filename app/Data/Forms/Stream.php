<?php namespace Data\Forms;

class Stream extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required',
        'fields' => 'required',
        'current_values' => '',
        'filter_field' => '',
        'filter_field_names' => ''
    ];

} 