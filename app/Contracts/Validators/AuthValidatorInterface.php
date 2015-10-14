<?php namespace Agency\Contracts\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface AuthValidatorInterface
{
    /**
     * Validate the given attributes as an Auth profile.
     *
     * @param  array $attributes
     *
     * @return bool
     * @throws \Agency\Exceptions\InvalidAuthProfileException
     */
    public function validate($attributes);
}
