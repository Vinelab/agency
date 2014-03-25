<?php namespace Agency\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;
use Agency\Cms\Authentication\AdminAuthenticator;
use Agency\Cms\Notifications\AdminRegistrationEmailNotifier;

class CmsServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->register('Agency\Cms\Authority\AuthorityServiceProvider');

        $this->app->bind('Agency\Cms\Authentication\Contracts\AuthenticatorInterface', function() {
            return new AdminAuthenticator($this->app->make('config'), $this->app->make('auth'));
        });

        $this->app->bind(
            'Agency\Cms\Repositories\Contracts\AdminRepositoryInterface',
            'Agency\Cms\Repositories\AdminRepository');

        $this->app->bind('Agency\Cms\Contracts\AdminInterface', 'Agency\Cms\Admin');

        $this->app->bind(
            'Agency\Cms\Authentication\Contracts\AdminAuthorizerInterface',
            'Agency\Cms\Authentication\AdminAuthorizer');

        $this->app->bind(
            'Agency\Cms\Repositories\Contracts\PostRepositoryInterface',
            'Agency\Cms\Repositories\PostRepository');

        $this->app->bind('Agency\Cms\Validators\SectionValidator', function(){
            return new \Agency\Cms\Validators\SectionValidator($this->app->make('validator'));
        });

        $this->app->bind('Agency\Cms\Validators\Contracts\AdminValidatorInterface', function(){
            return new \Agency\Cms\Validators\AdminValidator($this->app->make('validator'));
        });

        $this->app->bind(
            'Agency\Cms\Notifications\Contracts\AdminRegistrationNotifierInterface', function(){
                return new AdminRegistrationEmailNotifier($this->app->make('mailer'));
            });

        $this->app->bind(
            'Agency\Cms\Repositories\Contracts\RoleRepositoryInterface',
            'Agency\Cms\Repositories\RoleRepository');


        $this->app->bind(
            'Agency\Cms\Validators\Contracts\RoleValidatorInterface', function() {
                return new \Agency\Cms\Validators\RoleValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Cms\Validators\Contracts\ImageValidatorInterface', function() {
                return new \Agency\Cms\Validators\ImageValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Cms\Validators\Contracts\PostValidatorInterface', function() {
                return new \Agency\Cms\Validators\PostValidator($this->app->make('validator'));
            });

         $this->app->bind(
            'Agency\Cms\Validators\Contracts\VideoValidatorInterface', function() {
                return new \Agency\Cms\Validators\VideoValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Cms\Repositories\Contracts\PermissionRepositoryInterface',
            'Agency\Cms\Repositories\PermissionRepository');

        $this->app->bind(
            'Agency\Cms\Validators\Contracts\PermissionValidatorInterface', function() {
                return new \Agency\Cms\Validators\PermissionValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Cms\Validators\Contracts\TagValidatorInterface', function() {
                return new \Agency\Cms\Validators\TagValidator($this->app->make('validator'));
            });
    }
}
