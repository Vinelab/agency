<?php namespace Xfactor\Providers;

/**
 * @author Ibrahim Fleifel <Ibrahim@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;

class XfactorServiceProvider extends ServiceProvider {

    public function register()
    {
    	$this->app->bind(
            'Xfactor\Contracts\Validators\UserValidatorInterface', function(){
                return new \Xfactor\Validators\UserValidator($this->app->make('validator'));
            });

    	$this->app->bind(
            'Xfactor\Contracts\Validators\TeamValidatorInterface', function(){
                return new \Xfactor\Validators\TeamValidator($this->app->make('validator'));
            });

        $this->app->bind('Xfactor\Contracts\Repositories\UserRepositoryInterface','Xfactor\Repositories\UserRepository');  
        $this->app->bind('Xfactor\Contracts\Repositories\TeamRepositoryInterface','Xfactor\Repositories\TeamRepository');  
        $this->app->bind('Xfactor\Contracts\Repositories\ScoreRepositoryInterface','Xfactor\Repositories\ScoreRepository');  
        
        $this->app->bind('Xfactor\Contracts\Services\PhotosServiceInterface','Xfactor\Services\PhotosService');  
        $this->app->bind('Xfactor\Contracts\Services\UsersServiceInterface','Xfactor\Services\UsersService'); 
        $this->app->bind('Xfactor\Contracts\Services\ScoreServiceInterface','Xfactor\Services\ScoreService'); 
 		
        $this->app->bind('Xfactor\Contracts\Mappers\TeamMapperInterface','Xfactor\Mappers\TeamMapper'); 
        $this->app->bind('Xfactor\Contracts\Mappers\UserMapperInterface','Xfactor\Mappers\UserMapper'); 




        
    }
}
