<?php  namespace Agency\Cms\Repositories\Contracts;

interface TagRepositoryInterface {

	public function create($text);

	public function detach($post,$tag);

} 