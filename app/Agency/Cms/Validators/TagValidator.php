<?php namespace Agency\Cms\Validators;

use Agency\Cms\Exceptions\InvalidTagException;

class TagValidator extends Validator implements Contracts\TagValidatorInterface {

    protected $rules = [
        "text"=>"required|unique:tags"
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           // throw new InvalidTagException($validation->messages()->all());
           return false;
        }

        return true;
    }

}