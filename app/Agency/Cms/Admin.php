<?php namespace Agency\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use NeoEloquent;

use Illuminate\Auth\UserInterface;
use Agency\Contracts\Cms\RegistrableInterface;
use Vinelab\NeoEloquent\Eloquent\SoftDeletingTrait;
use Agency\Contracts\Cms\AuthorableInterface;
use Agency\Contracts\Cms\AdminInterface;

class Admin extends NeoEloquent implements  AdminInterface, AuthorableInterface, UserInterface, RegistrableInterface {

    use SoftDeletingTrait;

    protected $label = ['Admin', 'Cms'];

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    protected $dates = ['deleted_at'];

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function privileges()
    {
        return $this->hasMany('Agency\Cms\Auth\Authorization\Entities\Privilege', 'GRANTED');
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

    // Authorable methods
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getKey()
    {
        return $this->id;
    }
}
