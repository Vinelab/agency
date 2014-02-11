<?php  namespace Agency\Cms\Repositories\Contracts;

interface VideoRepositoryInterface {

	public function create($url,$title,$description,$thumbnail);

	public function validate_url($url);

}