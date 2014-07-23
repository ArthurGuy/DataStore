<?php namespace Data\Forms;

class Login extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'username' => 'required',
        'password' => 'required|min:8'
    ];

} 