<?php namespace Agency\Providers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Auth;
use Config;
use Illuminate\Support\ServiceProvider;
use Agency\Cms\Auth\Guard;
use Agency\Cms\Auth\UserProvider;
use Illuminate\Foundation\Application;

class AgencyServiceProvider extends ServiceProvider {

    public function boot()
    {
        Config::set('auth.driver', 'agency.management');
        Config::set('auth.model', 'Agency\Cms\Admin');

        Auth::extend('agency.management', function(Application $app)
        {
            $provider = new UserProvider($app->make('Illuminate\Contracts\Hashing\Hasher'), Config::get('auth.model'));

            return new Guard($provider, $app->make('Illuminate\Session\Store'));
        });

    }

    public function register()
    {
        // register API Service Provider
        // $this->app->register('Agency\Providers\ApiServiceProvider');

        // register Media Service Provider
        $this->app->register('Agency\Media\MediaServiceProvider');

        // register Cms Service Provider
        $this->app->register('Agency\Providers\CmsServiceProvider');

        $this->app->bind(
            'Agency\Contracts\Repositories\TagRepositoryInterface',
            'Agency\Repositories\TagRepository');

         // posts
        $this->app->bind(
            'Agency\Contracts\Repositories\PostRepositoryInterface',
            'Agency\Repositories\PostRepository');

         $this->app->bind(
            'Agency\Contracts\Repositories\ImageRepositoryInterface',
            'Agency\Repositories\ImageRepository');

        $this->app->bind(
            'Agency\Contracts\Repositories\VideoRepositoryInterface',
            'Agency\Repositories\VideoRepository');

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

        $this->app->bind(
                'Agency\Contracts\ImageInterface',
                'Agency\Image');

        $this->app->bind(
                'Agency\Contracts\VideoInterface',
                'Agency\Video');







        $this->app->bind('Agency\Contracts\HelperInterface','Agency\Support\Helper');



        $this->app->bind(
            'Agency\Contracts\Validators\PostValidatorInterface', function() {
                return new \Agency\Validators\PostValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Contracts\Validators\VideoValidatorInterface', function() {
                return new \Agency\Validators\VideoValidator($this->app->make('validator'));
            });

         $this->app->bind(
            'Agency\Contracts\Validators\ImageValidatorInterface', function() {
                return new \Agency\Validators\ImageValidator($this->app->make('validator'));
            });

          $this->app->bind(
            'Agency\Contracts\Validators\TagValidatorInterface', function() {
                return new \Agency\Validators\TagValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Repositories\Contracts\VideoRepositoryInterface',
            'Agency\Repositories\VideoRepository'
        );

    }
}
