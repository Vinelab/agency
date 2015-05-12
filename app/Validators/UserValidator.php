<?php namespace Agency\Validators;

use Agency\Exceptions\InvalidUserAttributesException;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class UserValidator extends Validator
{
    protected $rules = ['email' => 'required|email'];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ($validation->passes()) {
            return true;
        }

        throw new InvalidUserAttributesException();
    }

    public function validateUpdate($attributes)
    {
        $this->rules['access_token'] = 'required';

        $validation = $this->validation($attributes);

        if ($validation->passes()) {
            return true;
        }

        throw new InvalidUserAttributesException();
    }
}
