<?php namespace Agency\Providers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AuthServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(
            'Agency\Contracts\Cms\Repositories\PermissionRepositoryInterface',
            'Agency\Cms\Auth\Repositories\PermissionRepository');

        $this->app->bind(
            'Agency\Contracts\Cms\Repositories\PrivilegeRepositoryInterface',
            'Agency\Cms\Auth\Repositories\PrivilegeRepository');

        $this->app->bind(
            'Agency\Contracts\Cms\Repositories\RoleRepositoryInterface',
            'Agency\Cms\Auth\Repositories\RoleRepository');

        $this->app->bind(
            'Agency\Contracts\Cms\AdminInterface',
            'Agency\Cms\Admin');


    }
}
