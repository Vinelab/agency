<?php namespace Agency\Cms\Validators;

use Agency\Cms\Exceptions\InvalidVideoException;

class VideoValidator extends Validator implements Contracts\VideoValidatorInterface {

    protected $rules = [
        "url"=>"required"
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidVideoException($validation->messages()->all());
        }

        return true;
    }

}