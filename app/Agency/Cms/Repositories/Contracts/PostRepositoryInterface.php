<?php  namespace Agency\Cms\Repositories\Contracts;

interface PostsRepositoryInterface {

	public function create($title,$body,$user_id);

	public function getPostsByIds($ids);

	public function update($id,$title,$body,$user_id);

	public function assign(LinkableInterface $linker);

	public function unlink(LinkableInterface $linker);

	public function publish();

	public function set($post);

}