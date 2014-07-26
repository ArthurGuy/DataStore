<?php namespace Data\Forms;

class Variable extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|unique:variables,name,{id}',
        'value' => '    ',
        'type' => 'required',
    ];

} 