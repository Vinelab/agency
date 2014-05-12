<?php namespace Agency\Cms\Validators;

use Agency\Validators\Validator;
use Agency\Cms\Exceptions\InvalidRoleException;

class RoleValidator extends Validator implements Contracts\RoleValidatorInterface {

    protected $rules = [
        'title' => 'required|max:255',
        'alias' => 'required|alias|alpha_dash|max:255'
    ];

    public function validate($attributes)
    {
        $this->validator->extend('alias', function($attributes, $value, $parameters) {
            return ! preg_match('/[A-Z]/', $value);
        });

        $validation = $this->validation($attributes);

        if ($validation->fails())
        {
            throw new InvalidRoleException($validation->messages()->all());
        }

        return true;
    }
}
