<?php namespace Agency\Api\Validators;

use Agency\Api\Validators\Contracts\EncryptorValidatorInterface;

class EncryptorValidator Extends SystemValidator implements EncryptorValidatorInterface {

	protected $rules=[
	"key"=>"required",
	"data"=>"required"
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