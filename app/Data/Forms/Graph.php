<?php

namespace App\Data\Forms;

class Graph extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required',
        'streamId' => 'required',
        'field' => 'required',
        'time_period' => 'required',
        'filter' => '',
        'filter_field' => '',
    ];

} 