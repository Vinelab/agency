<?php namespace Agency\Cms\Validators;

use Agency\Cms\Exceptions\InvalidContentException;

class ContentValidator extends Validator implements Contracts\ContentValidatorInterface {

    protected $rules = [
        "title"=>"required|unique:contents",
        "parent_id"=>"required",
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidPostException($validation->messages()->all());
        }

        return true;
    }

}