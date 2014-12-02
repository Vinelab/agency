<?php namespace Agency\Validators;

use Agency\Validators\Validator;
use Agency\Exceptions\InvalidTagException;
use Agency\Contracts\Validators\TagValidatorInterface;

class TagValidator extends Validator implements TagValidatorInterface {

    protected $rules = [
        'text' => 'required|unique:Tag|max:255'
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidTagException($validation->messages()->all());
        }

        return true;
    }

}
