<?php namespace Agency\Api\Repositories\Contracts;

interface CodeRepositoryInterface {

	public function create($app_id,$code,$valid);
	
}