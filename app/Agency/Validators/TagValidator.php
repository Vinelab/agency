<?php namespace Agency\Validators;

use Agency\Validators\Validator;
use Agency\Exceptions\InvalidTagException;

class TagValidator extends Validator implements Contracts\TagValidatorInterface {

    protected $rules = [
        'text' => 'required|unique:tags|max:255'
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
