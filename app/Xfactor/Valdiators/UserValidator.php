<?php namespace Xfactor\Validators;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Lang;
use Agency\Validators\Validator;
use Xfactor\Contracts\Validators\UserValidatorInterface;
use Xfactor\Exceptions\InvalidUserException;

class UserValidator extends Validator implements UserValidatorInterface{

    protected $rules = [
        'name' => 'required',
        'gigya_id' => 'required',
        'avatar' => 'required'
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ($validation->fails())
        {
            throw new InvalidUserException($validation->messages()->all());
        }

        return true;
    }
}