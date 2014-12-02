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



    }
}
