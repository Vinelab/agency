<?php namespace Agency;

use NeoEloquent;
use Agency\Caching\Cacheable;
use Agency\Contracts\UserInterface;
use Agency\Observers\CachingObserver;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class User extends NeoEloquent implements UserInterface
{
    use Cacheable;

    protected $label = 'User';

    protected $fillable = ['name', 'email', 'avatar', 'blocked', 'email_verified', 'email_verified_at'];

    public function socialAccounts()
    {
        return $this->hasMany('Agency\SocialAccount', 'SOCIAL_ACCOUNT');
    }

    public function facebookAccount()
    {
        return $this->hasOne('Agency\FacebookSocialAccount', 'SOCIAL_ACCOUNT');
    }

    public function twitterAccount()
    {
        return $this->hasOne('Agency\TwitterSocialAccount', 'SOCIAL_ACCOUNT');
    }

    public function likesComments()
    {
        return $this->hasMany('Fahita\Comment', 'LIKES');
    }

    public function reportedComments()
    {
        return $this->hasMany('Fahita\Comment', 'REPORTED');
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function emailVerificationCodes()
    {
        return $this->hasMany('Agency\EmailVerificationCode', 'VERIFICATION_CODE');
    }
}

User::observe(new CachingObserver());
