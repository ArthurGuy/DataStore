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
        'tags' => '',
        'current_values' => ''
    ];

} 