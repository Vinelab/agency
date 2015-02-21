<?php namespace Xfactor\Validators;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Lang;
use Agency\Validators\Validator;
use Xfactor\Contracts\Validators\ScoreValidatorInterface;
use Xfactor\Exceptions\InvalidScoreException;

class ScoreValidator extends Validator implements ScoreValidatorInterface{

    protected $rules = [
        'title' => 'required'
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ($validation->fails())
        {
            throw new InvalidScoreException($validation->messages()->all());
        }

        return true;
    }