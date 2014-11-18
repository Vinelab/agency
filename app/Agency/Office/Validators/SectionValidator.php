<?php namespace Agency\Office\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Office\Exceptions\InvalidSectionException;

class SectionValidator extends Validator {

    protected $rules = [
        'title'      => 'required|max:255|min:3',
        'alias'      => 'alias|max:255|alpha_dash',
        'icon'       => 'max:20',
        'is_fertile' => 'required|boolean',
        'is_roleable'=> 'required|boolean'
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
            throw new InvalidSectionException($validation->messages()->all());
        }

        return true;
    }
}
