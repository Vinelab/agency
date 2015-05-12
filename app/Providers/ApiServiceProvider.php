<?php namespace Agency\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class ApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Agency\Contracts\Validators\AuthValidatorInterface', 'Agency\Api\Validators\AuthValidator');
        $this->app->bind(
            'Agency\Contracts\Repositories\SocialAccountRepositoryInterface',
            'Agency\Repositories\SocialAccountRepository'
        );
    }
}
