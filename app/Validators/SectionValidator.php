<?php namespace Agency\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Exceptions\InvalidSectionException;

class SectionValidator extends Validator {

    protected $rules = [
        'title'      => 'required|max:255|min:3',
        'alias'      => 'alias|max:255|alpha_dash',
        'icon'       => 'required|max:20',
        'parent_id'  => 'required|integer',
        'is_fertile' => 'required|integer',
        'is_roleable'=> 'required|integer'
    ];

    public function validate($attributes)
    {
        // an alias should not have an uppercase letter
        /**
         * @todo Improve this by adding validation error message through
         *       Translator. See http://laravel.com/docs/validation#custom-validation-rules
         */
        $this->validator->extend('alias', function($attributes, $value, $parameters) {
            return  ! preg_match('/[A-Z]/', $value);
        });

        $validation = $this->validation($attributes);

        if ($validation->fails())
        {
            /**
             * @todo Improve this to use the implementation of messages setting
             */
            throw new InvalidSectionException(serialize($validation->messages()->all()));
        }

        return true;
    }
}
