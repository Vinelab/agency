<?php namespace Agency\Providers\Office;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AuthServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(
            'Agency\Contracts\Office\Repositories\PermissionRepositoryInterface',
            'Agency\Office\Auth\Repositories\PermissionRepository');

        $this->app->bind(
            'Agency\Contracts\Office\Repositories\PrivilegeRepositoryInterface',
            'Agency\Office\Auth\Repositories\PrivilegeRepository');

        $this->app->bind(
            'Agency\Contracts\Office\Repositories\RoleRepositoryInterface',
            'Agency\Office\Auth\Repositories\RoleRepository');

        $this->app->bind(
            'Agency\Contracts\Office\AdminInterface',
            'Agency\Office\Admin');
    

    }
}