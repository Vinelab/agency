<?php namespace Agency\Office;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use NeoEloquent;

use Illuminate\Auth\UserInterface;
use Agency\Contracts\Office\RegistrableInterface;
use Vinelab\NeoEloquent\Eloquent\SoftDeletingTrait;
use Agency\Contracts\Office\AuthorableInterface;

class Admin extends NeoEloquent implements AuthorableInterface, UserInterface, RegistrableInterface {

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
        return $this->hasMany('Agency\Office\Auth\Authorization\Entities\Privilege', 'GRANTED');
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
}
