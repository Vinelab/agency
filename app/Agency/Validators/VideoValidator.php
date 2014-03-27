<?php namespace Agency\Validators;

use Agency\Exceptions\InvalidVideoException;

class VideoValidator extends Validator implements Contracts\VideoValidatorInterface {

    protected $rules = [
        'url' => 'required|url|max:255'
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
