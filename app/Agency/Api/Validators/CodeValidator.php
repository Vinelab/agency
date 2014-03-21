<?php namespace Agency\Api\Validators;

use Agency\Api\Validators\Contracts\CodeValidatorInterface;

class CodeValidator Extends SystemValidator implements CodeValidatorInterface {

	protected $rules=[
	"app_id"=>"required",
	"code"=>"required|unique:codes",
	"valid"=>"required|in:true,false"
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