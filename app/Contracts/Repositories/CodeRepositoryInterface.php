<?php namespace Agency\Contracts\Repositories;

interface CodeRepositoryInterface {

	public function create($app_id,$code,$valid);

}
