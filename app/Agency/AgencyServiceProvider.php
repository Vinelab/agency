<?php namespace Agency;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;

class AgencyServiceProvider extends ServiceProvider {

    public function register()
    {
        // register Media Service Provider
        $this->app->register('Agency\Media\MediaServiceProvider');

        // register Cms Service Provider
        $this->app->register('Agency\Cms\CmsServiceProvider');

        // register Api Service Provider
        $this->app->register('Agency\Api\ApiServiceProvider');

        // admin
        $this->app->bind(
            'Agency\Repositories\Contracts\AdminRepositoryInterface',
            'Agency\Repositories\AdminRepository');

        $this->app->bind('Agency\Contracts\AdminInterface', 'Agency\Admin');

        // images
        $this->app->bind(
            'Agency\Repositories\Contracts\ImageRepositoryInterface',
            'Agency\Repositories\ImageRepository');
        $this->app->bind('Agency\Contracts\ImageInterface', 'Agency\Image');
    }
}
