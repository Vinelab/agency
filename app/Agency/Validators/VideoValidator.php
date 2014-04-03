<?php namespace Agency\Validators;

use Agency\Exceptions\InvalidVideoException;

class VideoValidator extends Validator implements Contracts\VideoValidatorInterface {

    protected $rules = [
        'url' => 'required|url|max:255|youtube'
    ];

    public function validate($attributes)
    {

        $this->validator->extend('youtube', function($attributes, $value, $parameters){

            $pattern = '~
            ^(?:https?://)?              # Optional protocol
            (?:www\.)?                  # Optional subdomain
            (?:youtube\.com|youtu\.be)  # Mandatory domain name
            (/embed/([^&]+))?           # URI with video id as capture group 1
            ~x';
            return (boolean) preg_match($pattern, $value, $matches);
            
        });

        $validation = $this->validation($attributes);

        if ( ! $validation->passes())
        {
           throw new InvalidVideoException($validation->messages()->all());
        }

        return true;
    }

}
