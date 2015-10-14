<?php namespace Agency\Validators;

use Agency\Contracts\Validators\ImageValidatorInterface;

use Agency\Exceptions\InvalidImageException;

class ImageValidator extends Validator implements ImageValidatorInterface {

    protected $rules = [
    	'url'=>'required|url'
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidImageException($validation->messages()->all());
        }

        return true;
    }

}
