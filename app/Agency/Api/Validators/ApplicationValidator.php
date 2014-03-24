<?php namespace Agency\Api\Validators;

use Agency\Api\Validators\Contracts\ApplicationValidatorInterface;

class ApplicationValidator Extends SystemValidator implements ApplicationValidatorInterface {

	protected $rules = [
        "name"=>"required",
        "key"=>"required|unique:applications",
        "secret"=>"required"
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
        	return false;
        }

        return true;
    }

}