<?php namespace Agency\Cms;

use Eloquent;

use Illuminate\Auth\UserInterface;
use Agency\Contracts\AdminInterface;
use Agency\Cms\Contracts\RegistrableInterface;

use Agency\Cms\Authority\Contracts\AuthorableInterface;

class Admin extends Eloquent implements AdminInterface, AuthorableInterface, UserInterface, RegistrableInterface {

    protected $table = 'admins';

    protected $softDelete = true;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    public function getAuthIdentifier()
    {
        return $this->identifier();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function identifier()
    {
        return $this->id;
    }

    public function privileges()
    {
        return $this->morphMany('Agency\Cms\Authority\Entities\Privilege', 'resource');
    }

    public function getRegistrationPassword()
    {
        return $this->raw_password;
    }

    public function getRegistrationEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function posts()
    {
        return $this->hasMany("Agency\Cms\Post");
    }
}
