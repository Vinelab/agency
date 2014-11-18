<?php namespace Agency\Office\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Office\Exceptions\InvalidAdminException;
use Agency\Contracts\Office\Validators\AdminValidatorInterface;

class AdminValidator extends Validator implements AdminValidatorInterface {

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:Admin'
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidAdminException($validation->messages()->all());
        }

        return true;
    }

    public function validateForUpdate($attributes)
    {
        // override the email rule to not be required as unique across the admins
        $this->rules['email'] = 'required|email';

        return $this->validate($attributes);
    }
}
