<?php namespace Agency\Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('Agency\Api\Repositories\Contracts\ApplicationRepositoryInterface','Agency\Api\Repositories\ApplicationRepository');
        $this->app->bind('Agency\Api\Repositories\Contracts\CodeRepositoryInterface','Agency\Api\Repositories\CodeRepository');
        $this->app->bind('Agency\Api\EncryptorInterface','Agency\Api\Encryptor');
        
        $this->app->bind('Agency\Api\Validators\Contracts\ApplicationValidatorInterface','Agency\Api\Validators\ApplicationValidator');
        $this->app->bind('Agency\Api\Validators\Contracts\CodeValidatorInterface','Agency\Api\Validators\CodeValidator');
        $this->app->bind('Agency\Api\Validators\Contracts\SystemValidatorInterface','Agency\Api\Validators\SystemValidator');


    }
}