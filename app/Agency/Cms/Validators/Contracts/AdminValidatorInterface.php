<?php namespace Agency\Cms\Validators\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface AdminValidatorInterface {

    public function validateForUpdate($attributes);
}