<?php namespace Agency\Providers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;
use Agency\Cms\Auth\Authentication\AdminAuthenticator;
use Agency\Cms\Notifications\AdminRegistrationEmailNotifier;

class CmsServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->register('Agency\Providers\AuthServiceProvider');

        $this->app->bind('Agency\Contracts\Cms\AuthenticatorInterface', function() {
            return new AdminAuthenticator($this->app->make('config'), $this->app->make('auth'));
        });

        $this->app->bind(
            'Agency\Contracts\Cms\Repositories\SectionRepositoryInterface',
            'Agency\Cms\Repositories\SectionRepository');

        $this->app->bind(
            'Agency\Contracts\Cms\Repositories\AdminRepositoryInterface',
            'Agency\Cms\Repositories\AdminRepository');

        $this->app->bind(
            'Agency\Contracts\Cms\AdminAuthorizerInterface',
            'Agency\Cms\Auth\Authentication\AdminAuthorizer');

        $this->app->bind('Agency\Cms\Validators\SectionValidator', function(){
            return new \Agency\Cms\Validators\SectionValidator($this->app->make('validator'));
        });

        $this->app->bind('Agency\Contracts\Cms\Validators\AdminValidatorInterface', function(){
            return new \Agency\Cms\Validators\AdminValidator($this->app->make('validator'));
        });

        $this->app->bind(
            'Agency\Contracts\Cms\Notifications\AdminRegistrationNotifierInterface', function(){
                return new AdminRegistrationEmailNotifier($this->app->make('mailer'));
            });

        $this->app->bind(
            'Agency\Contracts\Cms\Repositories\RoleRepositoryInterface',
            'Agency\Cms\Auth\Repositories\RoleRepository');

        $this->app->bind(
            'Agency\Contracts\Cms\Validators\RoleValidatorInterface', function() {
                return new \Agency\Cms\Validators\RoleValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Contracts\Cms\Repositories\PermissionRepositoryInterface',
            'Agency\Cms\Auth\Repositories\PermissionRepository');

        $this->app->bind(
            'Agency\Contracts\Cms\Validators\PermissionValidatorInterface', function() {
                return new \Agency\Cms\Validators\PermissionValidator($this->app->make('validator'));
            });

        $this->app->bind('Agency\Contracts\Cms\PrivilegeEntityInterface',
                'Agency\Cms\Auth\Authorization\Entities\Privilege');

        $this->app->singleton('which', 'Agency\Support\Which\Which');
        $this->app->singleton('publisher', 'Agency\Support\Publisher\Publisher');


    }
}
