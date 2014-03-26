<?php namespace Agency\Cms\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Validators\Validator
use Agency\Cms\Exceptions\InvalidAdminException;

class AdminValidator extends Validator implements Contracts\AdminValidatorInterface {

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:admins'
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
