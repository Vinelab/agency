<?php namespace Agency\Api\Validators;

use Agency\Contracts\Api\Validators\CodeValidatorInterface;
use Agency\Contracts\Api\CodeManagerInterface;
use Agency\Api\Exceptions\InvalidCodeException;
use Illuminate\Validation\Factory as ValidatorFactory;
use Agency\Contracts\Repositories\CodeRepositoryInterface;

class CodeValidator Extends SystemValidator implements CodeValidatorInterface {

    protected $rules=[
    "app_id" => "required",
    "code"   => "required",
    "valid"  => "required|boolean"
    ];

    public function __construct(CodeManagerInterface $manager,
                                ValidatorFactory $validator,
                                CodeRepositoryInterface $code)
    {
        parent::__construct($validator);
        $this->manager = $manager;
        $this->code = $code;
    }

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);
        if ( ! $validation->passes())
        {
           throw new InvalidCodeException($validation->messages()->all());
        }


        return true;
    }

    public function validateCode($code)
    {
        if(!is_null($this->manager->get($code)))
        {
            return true;
        }

        throw new InvalidCodeException();


    }

}
