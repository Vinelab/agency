<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\ImageRepositoryInterface;
use DB;
use Agency\Cms\Post;

class PostRepository extends Repository implements PostRepositoryInterface {


	public function __construct(Post $post,
								ImageRepositoryInterface $image)
	{
		$this->post = $this->model = $post;
		$this->image = $image;
	}

	protected $section;

	public function set($post)
	{
		$this->post=$post;
	}

	public function create($title,$body,$admin_id,$section_id,$publish_date,$publish_state)
	{
		$post=$this->post->create(compact("title","body","admin_id","section_id","publish_date","publish_state"));
		$this->post=$post;
		return $post;
	}

	public function update($id,$title,$body,$admin_id,$section_id,$publish_date,$publish_state)
	{
		$post=$this->post->find($id);
		if(!is_null($post))
		{
			$post->title = $title;
			$post->body = $body;
			$post->admin_id = $admin_id;
			$post->section_id = $section_id;
			$post->publish_date = $publish_date;
			$post->publish_state = $publish_state;
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

		$posts=[];

		foreach ($ids as $key => $id) {
			$post=$this->post->find($id);
			$thumbnail="";
			if(!is_null($post->media()->first()))
			{
				$media=$post->media()->first()->media;
				if($media->type()=="image")
				{
					$image_id = $post->media()->first()->media->photo_id;
					$thumbnail =  $this->image->getThumbnail($image_id)->url;

				} else{
					$thumbnail = $media->thumbnail;
				}	
			}

			array_push($posts, ['data'=>$post,'thumbnail'=>$thumbnail]);
		}

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