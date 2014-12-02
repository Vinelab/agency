<?php namespace Agency\Cms\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Validation\Factory as ValidatorFactory;
use Agency\Contracts\Cms\Validators\ValidatorInterface;

abstract class Validator implements ValidatorInterface {

    /**
     * The validator instance.
     *
     * @var Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * The rules to validate against.
     *
     * @var array
     */
    protected $rules;

    /**
     * Create a new validator instance.
     *
     * @param Illuminate\Validator\Factory $validator
     */
    public function __construct(ValidatorFactory $validator)
    {
        $this->validator = $validator;
    }

    public function validation($attributes)
    {
        return $this->validator->make($attributes, $this->rules);
    }

    abstract function validate($attributes);
}
