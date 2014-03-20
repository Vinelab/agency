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

	public function create($title,$body,$admin_id,$section_id,$publish_date,$publish_state,$slug)
	{
		$post=$this->post->create(compact("title","body","admin_id","section_id","publish_date","publish_state","slug"));
		$this->post=$post;
		return $post;
	}

	public function update($id,$title,$body,$admin_id,$section_id,$publish_date,$publish_state,$slug)
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
			$post->slug = $slug;
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
					$post->setThumbnail($thumbnail);

				} else{
					$thumbnail = $media->thumbnail;
					$post->setThumbnail($thumbnail);

				}	
			}

			array_push($posts,$post);
		}

		return $posts;
	}

	public function delete($slug)
	{
		try {
            $this->model = $this->post->where("slug","=",$slug)->first();
            return $this->model->delete(); 
       	} catch (Exception $e) {
           return Response::json(['message'=>$e->getMessage()]);
       }
	}

	public function section($slug)
	{
		$post = $this->post->where("slug","=",$slug)->first();

		return $post->section()->first();
	}

	public function uniqSlug($slug)
	{
		if(!is_null($this->findBy("slug",$slug)))
		{
			return $slug.date('Y-m-d H:i:s');
		} else {
			return $slug;
		}
	}

	public function allPublished()
	{
		return $this->post->published();
	}

	public function fromSection($posts,$section)
	{
		return  $posts->where('section_id','=',$section->id);
	}

	public function findByIdOrSlug($idOrSlug)
	{
		try {
			return $this->find($idOrSlug);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

			try {
				return $this->findBy('slug',$idOrSlug);
			} catch (Exception $e) {
				return dd($e->getMessage());
			}
			
		}
	}

	

}