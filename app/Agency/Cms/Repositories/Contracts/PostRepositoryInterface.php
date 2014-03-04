<?php  namespace Agency\Cms\Repositories\Contracts;

interface PostRepositoryInterface {

	public function create($title,$body,$user_id,$section,$publish_date,$publish_state);

	public function getPostsByIds($ids);

	public function update($id,$title,$body,$user_id,$section,$publish_date,$publish_state);

	public function publish();

	public function set($post);

}