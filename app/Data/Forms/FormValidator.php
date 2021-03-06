<?php

namespace App\Data\Forms;

use App\Data\Exceptions\FormValidationException;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\Validator as ValidatorInstance;

abstract class FormValidator {

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var ValidatorInstance
     */
    protected $validation;

    /**
     *
     * @param Validator $validator
     */
    function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate the form data
     *
     * @param array $formData
     * @param null $id
     * @throws \App\Data\Exceptions\FormValidationException
     * @return mixed
     */
    public function validate(array $formData, $id=null)
    {
        $this->validation = $this->validator->make($formData, $this->getValidationRules(['id'=>$id]));

        if ($this->validation->fails())
        {
            throw new FormValidationException('Validation failed', $this->getValidationErrors());
        }

        return true;
    }

    /**
     * Get the validation rules
     *
     * @param array $replacements
     * @return array
     */
    protected function getValidationRules(array $replacements = [])
    {
        $rules = $this->rules;

        foreach ($rules as $name => $rule)
        {
            //This should be hard coded but for now it will do
            if (isset($replacements['id']))
            {
                $rules[$name] = str_replace('{id}', $replacements['id'], $rule);
            }
        }
        return $rules;
    }

    /**
     * Get the validation errors
     *
     * @return \Illuminate\Support\MessageBag
     */
    protected function getValidationErrors()
    {
        return $this->validation->errors();
    }

}