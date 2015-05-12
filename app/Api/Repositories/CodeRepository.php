<?php namespace Agency\Api\Repositories;

use Agency\Api\Repositories\contracts\CodeRepositoryInterface;
use Agency\Api\Code;
use Agency\Cms\Repositories\Repository;

class CodeRepository extends Repository implements CodeRepositoryInterface {

	/**
	 * The code instance
	 * @var Code
	 */
	protected $code;

	public function __construct(Code $code)
	{
		$this->model = $this->code = $code;
	}

	/**
	 * Create a new Code.
	 * @param  Integer $application_id
	 * @param  String $code
	 * @param  Boolean $valid
	 * @return Code
	 */
	public function create($application_id, $code, $valid)
	{
		return	$this->code->create(compact("application_id","code","valid"));

	}

}
