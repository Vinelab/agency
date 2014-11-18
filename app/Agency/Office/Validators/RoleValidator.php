<?php namespace Agency\Office\Validators;

use Agency\Office\Exceptions\InvalidRoleException;
use Agency\Contracts\Office\Validators\RoleValidatorInterface;

class RoleValidator extends Validator implements RoleValidatorInterface {

    protected $rules = [
        'title' => 'required|max:255',
        'alias' => 'alias|alpha_dash|max:255'
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
