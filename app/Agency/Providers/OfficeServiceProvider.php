<?php namespace Agency\Providers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;
use Agency\Office\Auth\Authentication\AdminAuthenticator;
use Agency\Office\Notifications\AdminRegistrationEmailNotifier;

class OfficeServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->register('Agency\Providers\Office\AuthServiceProvider');

        $this->app->bind('Agency\Contracts\Office\AuthenticatorInterface', function() {
            return new AdminAuthenticator($this->app->make('config'), $this->app->make('auth'));
        });

        $this->app->bind(
            'Agency\Contracts\Office\Repositories\SectionRepositoryInterface',
            'Agency\Office\Repositories\SectionRepository');

        $this->app->bind(
            'Agency\Contracts\Office\Repositories\AdminRepositoryInterface',
            'Agency\Office\Repositories\AdminRepository');

        $this->app->bind(
            'Agency\Contracts\Office\AdminAuthorizerInterface',
            'Agency\Office\Auth\Authentication\AdminAuthorizer');

        $this->app->bind('Agency\Office\Validators\SectionValidator', function(){
            return new \Agency\Office\Validators\SectionValidator($this->app->make('validator'));
        });

        $this->app->bind('Agency\Contracts\Office\Validators\AdminValidatorInterface', function(){
            return new \Agency\Office\Validators\AdminValidator($this->app->make('validator'));
        });

        $this->app->bind(
            'Agency\Contracts\Office\Notifications\AdminRegistrationNotifierInterface', function(){
                return new AdminRegistrationEmailNotifier($this->app->make('mailer'));
            });

        $this->app->bind(
            'Agency\Contracts\Office\Repositories\RoleRepositoryInterface',
            'Agency\Office\Auth\Repositories\RoleRepository');

        $this->app->bind(
            'Agency\Contracts\Office\Validators\RoleValidatorInterface', function() {
                return new \Agency\Office\Validators\RoleValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Contracts\Office\Repositories\PermissionRepositoryInterface',
            'Agency\Office\Auth\Repositories\PermissionRepository');

        $this->app->bind(
            'Agency\Contracts\Office\Validators\PermissionValidatorInterface', function() {
                return new \Agency\Office\Validators\PermissionValidator($this->app->make('validator'));
            });

        $this->app->bind('Agency\Contracts\Office\PrivilegeEntityInterface',
                'Agency\Office\Auth\Authorization\Entities\Privilege');

        $this->app->singleton('which', 'Agency\Support\Which\Which');
    }
}
