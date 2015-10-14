<?php namespace Agency\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class CmsAuth {

	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (! $this->auth->check()) {
			return redirect()->route('cms.login');
		}

		return $next($request);
	}

}
