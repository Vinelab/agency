<?php namespace Agency\Api\Repositories;

use Agency\Api\Repositories\Contracts\ApplicationRepositoryInterface;
use Agency\Api\Application;
use Agency\Cms\Repositories\Repository;

class ApplicationRepository extends Repository implements ApplicationRepositoryInterface {
	
	/**
	 * The Application instance
	 * @var Application
	 */
	protected $application;

	public function __construct(Application $application)
	{
		$this->model = $this->application = $application;
	}
	
	/**
	 * Create new Application
	 * @param  string $key  
	 * @param  string $secret 
	 * @return Application
	 */
	public function create($name,$key, $secret)
	{
		return $this->application->create(compact("name","key","secret"));
	}

	public function destroy($id)
	{
		return $this->remove($id);
	}

}