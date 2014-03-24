<?php namespace Agency\Cms\Validators;

use Agency\Cms\Exceptions\InvalidPostException;

class PostValidator extends Validator implements Contracts\PostValidatorInterface {

    protected $rules = [
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidPostException($validation->messages()->all());
        }

        return true;
    }

}