<?php

namespace App\Data\Forms;

class APIResponse extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required',
        'response' => 'required',
    ];

} 