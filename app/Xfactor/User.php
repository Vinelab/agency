<?php namespace Xfactor;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use NeoEloquent;


class User extends NeoEloquent {

    protected $label = 'User';

    protected $fillable = [ 
                            'name',
                            'gigya_id',
                            'avatar',
                            'country'
                            ];

    public $timestamps = true;


    public function getAuthIdentifier()
    {
        return $this->primaryKey;
    }

    public function name()
    {
        return $this->name;
    }

    public function gigyaId()
    {
        return $this->gigya_id;
    }

    public function avatar()
    {
        return $this->avatar;
    }

    public function country()
    {
        return $this->country;
    }

    public function score() 
    {
        return $this->hasOne("Xfactor\Score","SCORE");
    }

    public function team()
    {
        return $this->belongsTo("Xfactor\Team", "USER");
    }






}

