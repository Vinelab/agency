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
    }
}