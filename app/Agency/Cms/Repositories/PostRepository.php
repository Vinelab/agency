<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use DB;
use Agency\Cms\Post;

class PostRepository extends Repository implements PostRepositoryInterface {


	public function __construct(Post $post)
	{
		$this->post = $this->model = $post;
	}

	protected $section;

	public function set($post)
	{
		$this->post=$post;
	}

	public function create($title,$body,$admin_id,$section_id)
	{
		$post=$this->post->create(compact("title","body","admin_id","section_id"));
		$this->post=$post;
		return $post;
	}

	public function update($id,$title,$body,$admin_id)
	{
		$post=$this->post->find($id);
		if(!is_null($post))
		{
			$post->title = $title;
			$post->body = $body;
			$post->admin_id = $admin_id;
			$post->save();
			return $post;
		}
		return false;
	}

	public function publish()
	{
		if($this->post->published == true)
			$this->post->published = false;
		else
			$this->post->published = true;

		$this -> post -> save();
		return $this -> post;
	}

	public function getPostsByIds($ids)
	{
		$posts = DB::table('posts')->whereIn('id', $ids)->get();
		return $posts;
	}

	public function delete($id)
	{
		try {
            $this->model = $this->find($id);
            return $this->model->delete(); 
       	} catch (Exception $e) {
           return Response::json(['message'=>$e->getMessage()]);
       }
	}

	

}