<?php namespace Agency\Contracts\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface ContentValidatorInterface {

    /**
     * Validate the given attributes.
     *
     * @param array  $attributes
     *
     * @return boolean
     */
    public function validate($attributes);
}
