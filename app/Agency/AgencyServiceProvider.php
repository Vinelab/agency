<?php namespace Agency;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;

class AgencyServiceProvider extends ServiceProvider {

    public function register()
    {
        // register Media Service Provider
        $this->app->register('Najem\Media\MediaServiceProvider');

        // register Cms Service Provider
        $this->app->register('Najem\Cms\CmsServiceProvider');   
    }
}