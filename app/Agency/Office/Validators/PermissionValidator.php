<?php namespace Agency\Office\Validators;

use Agency\Office\Exceptions\InvalidPermissionException;
use Agency\Contracts\Office\Validators\PermissionValidatorInterface;

class PermissionValidator extends Validator implements PermissionValidatorInterface {

    protected $rules = [
        'title'       => 'required|max:255',
        'alias'       => 'alias|alpha_dash|max:255',
        'description' => 'max:1000'
    ];

    public function validate($attributes)
    {
        $this->validator->extend('alias', function($attributes, $value, $parameters) {
            return ! preg_match('/[A-Z]/', $value);
        });

        $validation = $this->validation($attributes);

        if ($validation->fails())
        {
            throw new InvalidPermissionException($validation->messages()->all());
        }

        return true;
    }
}
