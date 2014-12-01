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
        $this->app->register('Agency\Media\MediaServiceProvider');

        // register Cms Service Provider
        $this->app->register('Agency\Providers\OfficeServiceProvider');

        $this->app->bind(
            'Agency\Contracts\Repositories\TagRepositoryInterface',
            'Agency\Repositories\TagRepository');

         // posts
        $this->app->bind(
            'Agency\Contracts\Repositories\PostRepositoryInterface',
            'Agency\Repositories\PostRepository');


        $this->app->bind(
            'Agency\Contracts\Repositories\ApplicationRepositoryInterface',
            'Agency\Repositories\ApplicationRepository');

        $this->app->bind(
            'Agency\Contracts\ApplicationInterface', 
            'Agency\Api\Application');

        $this->app->bind(
            'Agency\Contracts\Repositories\CodeRepositoryInterface',
            'Agency\Repositories\CodeRepository');

        $this->app->bind(
            'Agency\Contracts\CodeInterface', 
            'Agency\Api\Code');

        $this->app->bind(
            'Agency\Api\Encryptors\EncryptorInterface',
            'Agency\Api\Encryptors\Encryptor');

        $this->app->bind(
            'Agency\Api\Validators\Contracts\EncryptorValidatorInterface',
            'Agency\Api\Validators\EncryptorValidator');

        $this->app->bind(
            'Agency\Contracts\Api\CodeManagerInterface',
            'Agency\Api\CodeManager');

        $this->app->bind(
            'Agency\Contracts\Api\Validators\CodeValidatorInterface',
            'Agency\Api\Validators\CodeValidator');

        $this->app->bind(
            'Agency\Contracts\Cache\CacheManagerInterface',
            'Agency\Cache\CacheManager');
      $this->app->bind(
                'Agency\Contracts\Api\ApiInterface',
                'Agency\Api\Api');











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

        $this->app->bind('Agency\Contracts\HelperInterface','Agency\Support\Helper');



        $this->app->bind(
            'Agency\Contracts\Validators\PostValidatorInterface', function() {
                return new \Agency\Validators\PostValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Contracts\Validators\VideoValidatorInterface', function() {
                return new \Agency\Validators\VideoValidator($this->app->make('validator'));
            });



    }
}
