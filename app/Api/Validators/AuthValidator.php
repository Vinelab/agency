<?php namespace Agency\Api\Validators;

use Agency\Exceptions\InvalidAuthProfileException;
use Agency\Exceptions\InvalidVerificationCodeException;
use Agency\Contracts\Validators\AuthValidatorInterface;

/**
 * Validate auth profiles.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class AuthValidator extends SystemValidator implements AuthValidatorInterface
{
    protected $rules = [
        'id'     => 'required',
        'name'   => 'required',
        'avatar' => 'required',
        'email'  => 'sometimes|email',
    ];

    protected $code_rules = [
        'code' => 'required',
    ];

    /**
     * Validate the given attributes as an Auth profile.
     *
     * @param  array $attributes
     *
     * @return bool
     * @throws \Agency\Exceptions\InvalidAuthProfileException
     */
    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ($validation->passes()) {
            return true;
        }

        throw new InvalidAuthProfileException();
    }

    public function validateCode($attributes)
    {
        $validation = $this->validation($attributes, $this->code_rules);

        if ($validation->passes()) {
            return true;
        }

        throw new InvalidVerificationCodeException;
    }
}
