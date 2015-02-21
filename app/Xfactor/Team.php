<?php namespace Xfactor;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use NeoEloquent;

class Team extends NeoEloquent {

    protected $label = 'Team';

    protected $fillable = [ 
                            'title',
                            'slug',
                            'score',
                            'user_count'
                            ];

    public $timestamps = true;


    public function getAuthIdentifier()
    {
        return $this->primaryKey;
    }

    public function title()
    {
        return $this->title;
    }

    public function slug()
    {
        return $this->slug;
    }

    public function score()
    {
        return $this->score;
    }

    public function userCount()
    {
        return $this->user_count;
    }

    public function image()
    {
        return $this->hasOne('\Agency\Image','IMAGE');
    }

    public function users() 
    {
        return $this->hasMany('\Xfactor\User', 'USER');

    }

    public function total()
    {
        return $this->users()->count();
    }

}

