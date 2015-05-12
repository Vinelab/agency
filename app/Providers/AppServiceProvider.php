<?php namespace Agency\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		Blade::setRawTags('{{', '}}');

		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'Agency\Services\Registrar'
		);

		$this->app->register('Agency\Providers\AgencyServiceProvider');
		$this->app->register('Agency\Providers\AuthServiceProvider');
		$this->app->register('Agency\Providers\CmsServiceProvider');
		// $this->app->register('Agency\Providers\AblaFahitaServiceProvider');
	}

}
