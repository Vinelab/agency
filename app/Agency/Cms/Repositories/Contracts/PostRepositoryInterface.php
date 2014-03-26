<?php  namespace Agency\Cms\Repositories\Contracts;

interface PostRepositoryInterface {

	public function create($title,$body,$user_id,$section,$publish_date,$publish_state,$slug);

	public function getPostsByIds($ids);

	public function update($id,$title,$body,$user_id,$section,$publish_date,$publish_state,$slug);

	public function publish();

	public function set($post);

	public function section($id);

	public function uniqSlug($slug);

	public function allPublished($input);

	public function fromSection($posts,$section);

	public function detachImageFromPost($post_id,$image_id);

}