<?php namespace Agency\Cms\Validators;

use Agency\Cms\Exceptions\InvalidImageException;

class ImageValidator extends Validator implements Contracts\ImageValidatorInterface {

    protected $rules = [
    	"url"=>"required"
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