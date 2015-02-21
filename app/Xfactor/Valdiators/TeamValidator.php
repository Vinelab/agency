<?php namespace Xfactor\Validators;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Lang;
use Agency\Validators\Validator;
use Xfactor\Contracts\Validators\TeamValidatorInterface;
use Xfactor\Exceptions\InvalidTeamException;

class TeamValidator extends Validator implements TeamValidatorInterface {

    protected $rules = [
        'title' => 'required',
        'photo' => 'required'
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ($validation->fails())
        {
            throw new InvalidTeamException($validation->messages()->all());
        }

        return true;
    }
}