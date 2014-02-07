<?php namespace Agency\Cms\Authority;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AuthorityServiceProvider extends ServiceProvider {

    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Authority', 'Agency\Cms\Authority\Facades\Authority');

        $this->app->bind(
            'Agency\Cms\Authority\Repositories\Contracts\PermissionRepositoryInterface',
            'Agency\Cms\Authority\Repositories\PermissionRepository');

        $this->app->bind(
            'Agency\Cms\Authority\Repositories\Contracts\PrivilegeRepositoryInterface',
            'Agency\Cms\Authority\Repositories\PrivilegeRepository');

        $this->app->bind(
            'Agency\Cms\Authority\Repositories\Contracts\RoleRepositoryInterface',
            'Agency\Cms\Authority\Repositories\RoleRepository');
    }
}