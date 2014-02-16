<?php namespace Agency\Cms\Repositories\Contracts;

interface ContentRepositoryInterface {

	public function create($title,$url,$parent_id);

	public function update($id,$title,$parent_id);

	public function set($content);

	public function findBy($attribute,$value);

	public function delete($id);

}