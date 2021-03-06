<?php namespace Agency\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Validation\Factory as ValidatorFactory;

abstract class Validator implements Contracts\ValidatorInterface {

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

    public function validation($attributes, $rules = [])
    {
        if (empty($rules)) {
            $rules = $this->rules;
        }

        return $this->validator->make($attributes, $rules);
    }

    abstract function validate($attributes);
}
