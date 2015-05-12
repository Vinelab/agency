<?php namespace Agency\Providers;

use App;
use Config;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'Agency\Http\Controllers';

	/**
     * Supported domains.
     *
     * @var array
     */
    private $domains = ['api', 'cms'];

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

		//
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			$this->loadSiteRoutes($this->site(Request::capture()));
		});
	}

	/**
	 * Load the routes file for the given site.
	 *
	 * @param  string $site
	 *
	 * @return
	 */
	protected function loadSiteRoutes($site)
	{
    	require (empty($site)) ? app_path().'/Http/routes.php' : app_path()."/Http/routes/{$site}.php";
	}

	/**
     * Get the currently visited site's sub-domain.
     *
     * @return string
     */
	protected function site(Request $request)
	{
		$url = parse_url($this->getUrl($request));

		$host = $url['host'];
		// find matching supported domains
        $matches = array_filter($this->getSubDomains(), function($domain) use($host)
        {
            preg_match("/{$domain}.*/", $host, $matches);
            if($matches) return $domain;
        });

        return reset($matches);
	}

	/**
     * Get the supported sub domains.
     *
     * @return array
     */
    public function getSubDomains()
    {
        return $this->domains;
    }

    /**
     * Get the corresponding URL for the given request.
     * Also checks whether we're running in the testing environment
     * to make sure it sends the configured URL instead of the visiting
     * URL since testing does not support it.
     *
     * @param Illuminate $request
     *
     * @return string
     */
    public function getUrl($request)
    {
    	if ($this->isTesting()) return $this->getTestingUrl($request);

    	return $request->url();
    }

    /**
     * Detect whether this request is in the testing environment.
     *
     * @return boolean
     */
    protected function isTesting()
    {
        return App::environment() === 'testing';
    }

    /**
     * Return the URL based on the URL in the config.
     *
     * @return string
     */
    public function getTestingUrl(Request $request)
    {
        return Config::get('app.url') .'/'. $request->path();
    }
}
