<?php namespace Agency\Providers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;

class AgencyServiceProvider extends ServiceProvider {

    public function register()
    {
        // register API Service Provider
        // $this->app->register('Agency\Providers\ApiServiceProvider');

        // register Media Service Provider
        // $this->app->register('Agency\Providers\MediaServiceProvider');

        // register Cms Service Provider
        $this->app->register('Agency\Providers\OfficeServiceProvider');

        // register API V1 Mappers Service Provider
        // $this->app->register('Agency\Providers\Artists\Api\MappersServiceProvider');

        // Agency stuff registration starts here

        // Login

        // $this->app->bind('Agency\Contracts\Validators\SocialProfileValidatorInterface', function(){
        //     return new Validators\SocialProfileValidator($this->app->make('validator'));
        // });

        // $this->app->bind(
        //     'Agency\Contracts\Repositories\UserRepositoryInterface',
        //     'Agency\Repositories\UserRepository'
        // );

        // $this->app->singleton('Agency\Login\Store\StoreInterface',function(){
        //     return new Login\Store\RedisStore($this->app->make('redis'));
        // });

        // $this->app->bind('Agency\Login\Store\StoreManagerInterface', function(){
        //     return new Login\Store\StoreManager(
        //         $this->app->make('Agency\Login\Store\StoreInterface'),
        //         $this->app->make('cache')
        //     );
        // });

        //  $this->app->bind(
        //     'Agency\Login\SocialLoginInterface',
        //     'Agency\Login\SocialAuthenticator'
        // );

        // $this->app->bind('Agency\Login\SessionManagerInterface', 'Agency\Login\SessionManager');

        // $this->app->bind('Agency\Contracts\SocialUserInterface', 'Agency\User');

        // $this->app->bind('Agency\Contracts\UserProfileInterface', 'Agency\UserProfile');

        // posts
        // $this->app->bind('Agency\Contracts\PostInterface', 'Agency\Post');
        // $this->app->bind('Agency\Contracts\Post\StatusInterface', 'Agency\Post\Status');

        // $this->app->bind('Agency\Contracts\Post\UrlFactoryInterface', 'Agency\Post\Factories\UrlFactory');
        // $this->app->bind('Agency\Contracts\Post\HashtagFactoryInterface', 'Agency\Post\Factories\HashtagFactory');

        // $this->app->bind(
        //     'Agency\Contracts\Post\StatusRepositoryInterface',
        //     'Agency\Post\Repositories\StatusRepository');

        // $this->app->bind('Agency\Contracts\HelperInterface','Agency\Support\Helper');
    }
}
