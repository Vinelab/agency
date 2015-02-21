<?php namespace Xfactor;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use NeoEloquent;
use Illuminate\Auth\UserInterface;


class Score extends NeoEloquent {

    protected $label = 'Score';

    protected $fillable = [ 
                            'sharing',
                            'commenting',
                            'chatting',
                            'others'
                            ];

    public $timestamps = true;


    public function getAuthIdentifier()
    {
        return $this->primaryKey;
    }

    public function sharing()
    {
        return $this->sharing;
    }

    public function commenting()
    {
        return $this->commenting;
    }

    public function chatting()
    {
        return $this->chatting;
    }

    public function others()
    {
        return $this->others;
    }

    public function user()
    {
        return $this->belongsTo('Xfactor\User','SCORE');
    }

}

