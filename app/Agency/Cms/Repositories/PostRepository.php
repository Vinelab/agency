<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\PostsRepositoryInterface;
use Agency\Cms\Repositories\Contracts\LinkableInterface;
use DB;
use Agency\Cms\Post;
use Agency\Cms\Linker;

class PostRepository extends Repository implements PostsRepositoryInterface {


	public function __construct(Post $post)
	{
		$this->post = $this->model = $post;
	}

	protected $section;

	public function set($post)
	{
		$this->post=$post;
	}

	public function create($title,$body,$admin_id)
	{
		$post=$this->post->create(compact("title","body","admin_id"));
		$this->post=$post;
		return $post;
	}

	public function update($id,$title,$body,$user_id)
	{
		$post=$this->post->find($id);
		if(!is_null($post))
		{
			$post->title = $title;
			$post->body = $body;
			$post->user_id = $user_id;
			$post->save();
			return $post;
		}
		return false;
	}

	public function assign( LinkableInterface $linker)
	{
		$link = $linker->linker()
			->create(["post_id" => $this->post->id]);

		if ( ! is_null($link))
		{
			return $link;
		}

		return false;
	}

	public function unlink ( LinkableInterface $lnker )
	{
		$link = Linker::where ( "post_id", "=", $this -> post -> id ) 
				-> where ( "linkable_id", "=", $linker -> getIdentifier() ) 
				-> where ( "linkable_type", "=", get_class ( $linker -> getInstance() ) )
				-> get() -> first();

		$result = $link -> delete();
		return $result;
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

}