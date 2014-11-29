<?php namespace Agency\Api\Validators;

use Agency\Api\Validators\Contracts\ApplicationValidatorInterface;
use Agency\Api\Exceptions\InvalidApplicationException;


class ApplicationValidator Extends SystemValidator implements ApplicationValidatorInterface {

    protected $rules = [
        "name"=>"required",
        "key"=>"required|unique:Application",
        "secret"=>"required"
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidApplicationException($validation->messages()->all());
        }

        return true;
    }

}
