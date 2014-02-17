<?php  namespace Agency\Cms\Repositories\Contracts;

interface PostRepositoryInterface {

	public function create($title,$body,$user_id,$section);

	public function getPostsByIds($ids);

	public function update($id,$title,$body,$user_id);

	public function publish();

	public function set($post);

}