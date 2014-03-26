<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Repositories\Contracts\ImageRepositoryInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;
use DB,Config, Response;
use Agency\Cms\Post;
use Agency\Api\Mappers\PostMapper;
use Agency\Api\Api;

use Exception;


class PostRepository extends Repository implements PostRepositoryInterface {


	public function __construct(Post $post,
								ImageRepositoryInterface $image,
								SectionRepositoryInterface $sections)
	{
		$this->post = $this->model = $post;
		$this->image = $image;
		$this->sections = $sections;
		$this->postMapper = new PostMapper();
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
				return json_encode(['status'=>400,'messages'=>$e->getMessage()]);
			}
			
		}
	}

	public function allPublished($input)
	{

		 $posts = $this->post->published()->latest('created_at');
        
        if(isset($input['category']) and !empty($input['category']))
        {   
            $section = $this->sections->findBy('alias',$input['category']);
            if(!is_null($section))
            {
            	$posts = $this->fromSection($posts,$section);
            } else {
            	return Response::Json(['status'=>400,'Message'=>'category not found!']);
            }

            // $posts = $posts->join('cms_sections', 'cms_sections.id','=','posts.section_id')->where('alias','=',Input::get('category'));
            // return dd($posts->first());
        }

        if(isset($input['tag']) and ! empty($input['tag']))
        {
            $posts=$posts->whereHas('tags',function($q) use ($input){
            		return $q->where('slug','=',$input['tag']);
            });
        }

    	if (isset($input['limit']) and ! empty($input['limit']))
    	{
            $paginated_posts = $posts->paginate((int)$input['limit']);
    	}else{
            $paginated_posts = $posts->paginate(Config::get('api.limit'));
        }

        	return $paginated_posts;
       
	}

	public function detachImageFromPost($post_id,$image_id)
	{
		try {

			$post = $this->find($post_id);

			$image = $post->media()->where('media_type','=','Agency\Image')->where('media_id','=',$image_id)->first();
			
			$images = $this->image->getByGuid($image->media->guid);
			
			$images_ids = $images->lists('id');
			$this->image->groupDelete($images_ids);
			
			$image->delete();
			
			return true;

		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function detachAllVideos($post_id)
	{
		$post = $this->find($post_id);
		$videos=$post->media()->where('media_type','=','Agency\Video')->get();
		$videos->each(function($video){
			return $video->delete();
		});
		

	}

	

}