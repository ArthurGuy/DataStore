<?php namespace Data\Forms;

class Trigger extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name'              => 'required',
        'streamId'          => 'required|exists:streams,id',
        'check_field'       => 'required',
        'check_operator'    => 'required',
        'check_value'       => 'required',
        'filter_field'      => '',
        'filter_value'      => 'required_with:filter_field',
        'action'            => 'required',
        'push_subject'      => 'required_if:action,push_message',
        'push_message'      => 'required_if:action,push_message',
        'variable_name'     => 'required_if:action,variable',
        'variable_value'    => 'required_if:action,variable',
        'nest_api_key'      => 'required_if:action,nest',
        'nest_property'     => 'required_if:action,nest',
        'nest_value'        => 'required_if:action,nest',
        'nest_structure'    => 'required_if:action,nest',
        'location_id'       => 'required_if:action,location',
    ];

} 