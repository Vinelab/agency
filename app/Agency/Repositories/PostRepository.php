<?php  namespace Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Post;
use Agency\Helper;
use Agency\Api\Api;
use DB,Config, Response;
use Agency\Api\Mappers\PostMapper;
use Agency\Contracts\ImageInterface;
use Agency\Contracts\VideoInterface;
use Agency\Repositories\Contracts\PostRepositoryInterface;
use Agency\Repositories\Contracts\ImageRepositoryInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;


class PostRepository extends Repository implements PostRepositoryInterface {


	/**
	 * the post model instance
	 * 
	 * @var Agency\Post
	 */
	protected $post;

	protected $image;

	protected $images;

	protected $sections;


	public function __construct(Post $post,
								ImageRepositoryInterface $images,
								SectionRepositoryInterface $sections,
								ImageInterface $image,
								VideoInterface $video)
	{
		$this->post = $this->model = $post;
		$this->images = $image;
		$this->sections = $sections;
		$this->image = $image;
		$this->video = $video;
	}

	protected $section;

	public function create($title, $slug, $body, $admin_id, $section_id, $publish_date, $publish_state)
	{
		return $this->post->create(compact("title","body","admin_id","section_id","publish_date","publish_state","slug"));
	}

	public function update($id, $title, $slug, $body, $admin_id, $section_id, $publish_date, $publish_state)
	{
		$post = $this->find($id);
		$post->fill(compact('title', 'slug', 'body', 'admin_id', 'section_id', 'publish_date', 'publish_state'));

		if ($post->save())
		{
			return $post;
		}
	}

	public function get($ids)
	{
		return $this->post->with('media')->whereIn($ids)->get();
	}

	/**
	 * @override
	 *
	 * @param {int|string} $id_or_slug
	 * @return boolean
	 */
	public function remove($id_or_slug)
	{
		return $this->findByIdOrSlug($id_or_slug)->delete();
	}

	public function uniqSlug($title)
	{
		return Helper::slugify($title, $this->post);
	}

	public function forSection($section_id)
	{
		return  $this->post->where('section_id', $section_id)->get();
	}

	public function findByIdOrSlug($id_or_slug)
	{
		try {
			return $this->find($id_or_slug);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return $this->findBy('slug',$id_or_slug);
		}
	}

	public function published($input = array())
	{
		$posts = $this->post->published()->latest('created_at');

        if(isset($input['category']) and ! empty($input['category']))
        {
			$posts = $posts->join('cms_sections', 'cms_sections.id', '=', 'posts.section_id')
				->where('cms_sections.alias', $input['category']);
        }

        if(isset($input['tag']) and ! empty($input['tag']))
        {
            $posts = $posts->whereHas('tags',function($q) use ($input){
            	return $q->where('slug','=',$input['tag']);
            });
        }

		$limit = (isset($input['limit']) and ! empty($input['limit'])) ? $input['input'] : Config::get('api.limit');

        $paginated_posts = $posts->paginate((int) $input);

		$posts->select('posts.id as id', 'posts.title as title', 'posts.body as body');

        return $posts->get();
	}

	public function addTags($id, $new_tags, $existing_tags)
	{	
		$post = $this->find($id);
		$new_tags = $post->tags()->saveMany($new_tags);
		$new_tags_ids = array_map(function($tag){ return $tag->id; }, $new_tags);
		$tags_ids = array_merge($new_tags_ids, $existing_tags);
		$post->tags()->sync($tags_ids);
	}

	public function detachImages($id, $image_guids)
	{
		$this->post->where('id', $id)->whereHas('media', function($q){
			$q->where('media_type', get_class($this->image))
				->whereIn('guid', $image_guids);
		})->detach();

		// $post = $this->find($post_id);

		// $image = $post->media()
		// 	->where('media_type', get_class($this->image))->where('media_id','=',$image_id)->first();
		
		// $images = $this->images->getByGuid($image->media->guid);
		
		// $images_ids = $images->lists('id');
		// $this->images->groupDelete($images_ids);
		
		// $image->delete();
		
		return true;
	}

	public function detachAllVideos($post_id)
	{
		try {

			$post = $this->find($post_id);
			$videos=$post->media()->where('media_type', get_class($this->video))->get();
			$videos->each(function($video){
				return $video->delete();
			});
			
		} catch (Exception $e) {

			return $e->getMessage();
		}
		
	}

	public function addImages($post_id, $images)
	{
		$post = $this->find($post_id);
		$result = $post->images()->saveMany($images);
		return dd($result);
	}






}
