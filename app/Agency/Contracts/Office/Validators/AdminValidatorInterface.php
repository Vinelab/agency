<?php namespace Agency\Contracts\Cms\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface AdminValidatorInterface {

    public function validateForUpdate($attributes);
}
