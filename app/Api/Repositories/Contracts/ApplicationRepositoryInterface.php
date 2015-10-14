<?php namespace Agency\Api\Repositories\Contracts;

interface ApplicationRepositoryInterface {

	public function create($name,$app_id,$app_secret);

}
