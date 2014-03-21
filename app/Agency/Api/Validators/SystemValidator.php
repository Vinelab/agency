<?php namespace Agency\Api\Validators;

use Illuminate\Validation\Factory as ValidatorFactory;

use Agency\Api\Validators\Contracts\SystemValidatorInterface;

abstract class SystemValidator implements SystemValidatorInterface {
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