<?php namespace Agency\Validators;

use Agency\Exceptions\InvalidPostException;
use Agency\Contracts\Validators\PostValidatorInterface;

class PostValidator extends Validator implements PostValidatorInterface {

    protected $rules = [
        'title' => 'required|max:255'
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
