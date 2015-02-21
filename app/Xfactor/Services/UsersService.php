<?php namespace Xfactor\Services;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Xfactor\Contracts\Services\UsersServiceInterface;
use Xfactor\Contracts\Validators\UserValidatorInterface;
use Xfactor\Exceptions\InvalidUserException;
use Xfactor\Contracts\Repositories\ScoreRepositoryInterface;
use Xfactor\Contracts\Repositories\UserRepositoryInterface;
use Api;
use Input;


class UsersService implements UsersServiceInterface{

    
    public function __construct(UserValidatorInterface $validator,
                                ScoreRepositoryInterface $score,
                                UserRepositoryInterface $users) 
    {
        $this->validator = $validator;
        $this->score = $score;
        $this->users = $users;
    }

    public function create()
    {
        try {

            $this->validator->validate(Input::all());
            $relations['score'] = $this->score->create(0,0,0,0);
            return $this->users->createWith(Input::get('name'),
                                             Input::get('gigya_id'),
                                             Input::get('avatar'),
                                             Input::get('country'),
                                             $relations);
            
        } catch (InvalidUserException $e) {
            return Api::error($e, $e->getCode(), 401);      

        }
    }


}
