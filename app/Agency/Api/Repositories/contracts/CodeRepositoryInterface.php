<?php namespace Agency\Api\Repositories\contracts;

interface CodeRepositoryInterface {

	public function create($app_id,$code,$valid);
	
}