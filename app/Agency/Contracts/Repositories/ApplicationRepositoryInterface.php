<?php namespace Agency\Contracts\Repositories;

interface ApplicationRepositoryInterface {

	public function create($name,$app_id,$app_secret);
	
}