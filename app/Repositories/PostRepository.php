<?php  namespace Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Post;
use Carbon\Carbon;
use Agency\Helper;
use Agency\Api\Api;
use DB,Config, Response;
use Agency\Contracts\Repositories\PostRepositoryInterface;
use Agency\Contracts\Cms\Repositories\SectionRepositoryInterface;
use Agency\Contracts\HelperInterface;

use Exception;


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
								SectionRepositoryInterface $sections,
								HelperInterface $helper)
	{
		$this->post = $this->model = $post;
		$this->sections = $sections;
		$this->helper = $helper;
	}

	protected $section;

	public function create($title, $slug, $body, $admin_id, $section_id, $publish_date, $publish_state)
	{
		return $this->post->create([
			"title"         => $title,
			"slug"          => $slug,
			"body"          => $body,
			"admin_id"      => $admin_id,
			"section_id"    => $section_id,
			"publish_date"  => $publish_date,
			"publish_state" => $publish_state
		]);
	}


	public function createWith($title, $slug, $body, $featured, $publish_date, $publish_state, $share_url, $relations = [])
	{
		return $this->post->createWith([
			'title'         => $title,
			'slug'          => $slug,
			'body'          => $body,
			'featured'      => $featured,
			'publish_date'  => $publish_date,
			'publish_state' => $publish_state,
			'share_url' => $share_url
		], $relations);
	}

	public function update($id, $title, $slug, $body, $featured, $publish_date, $publish_state, $share_url)
	{
		$post = $this->find($id);
		$post->fill([
			'title'         => $title,
			'slug'          => $slug,
			'body'          => $body,
			'featured'      => $featured,
			'publish_date'  => $publish_date,
			'publish_state' => $publish_state,
			'share_url' => $share_url
		]);

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
		$post = $this->findByIdOrSlug($id_or_slug);
		$post->section()->edge($post->section)->delete();
		return $post->delete();
	}

	public function uniqSlug($title)
	{
		return $this->helper->slugify($title, $this->post);
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

		$posts = $this->post->published();

		if(isset($input['keyword']) and ! empty($input['keyword']))
		{
			$posts->where('body','=~','.*'.$input['keyword'].'.*')->orWhere('title', '=~', '.*'.$input['keyword'].'.*');
		}

		if(isset($input['featured']) and ! empty($input['featured']))
        {
        	$featured = ((boolean)$input['featured'] == 1) ? 'true' : 'false';
        	$posts = $posts->where('featured',$featured);
        }

        if(isset($input['category']) and ! empty($input['category']))
        {
        	$posts = $posts->whereHas('section', function($q) use ($input) {
        		return $q->where('alias','=',$input['category']);
        	});
        }


        if(isset($input['tag']) and ! empty($input['tag']))
        {
            $posts = $posts->whereHas('tags',function($q) use ($input){
            	return $q->where('slug','=',$input['tag']);
            });
        }

        return $posts;


	}


	public function paginatedPublishedPost($input = array())
	{
		$posts = $this->published($input);

		$posts = $posts->orderBy('publish_date','desc');

		$limit = (isset($input['limit']) and ! empty($input['limit'])) ? $input['limit'] : Config::get('api.limit');
		$limit = $this->checkLimit($limit);
		return $posts->get();
        // return  $posts->paginate($limit);

	}

	public function addTags($id, $tags_ids)
	{
		$post = $this->post->findOrFail($id);
		return $post->tags()->attach($tags_ids);
	}

	public function detachTags($id)
	{

		$post = $this->post->findOrFail($id);
		return $post->tags()->detach($post->tags->lists('id'));
	}

	public function detachImages($id, $image_ids)
	{

		return $this->post->findOrFail($id)->images()->detach($image_ids);
	}

	public function detachVideos($id, $video_ids)
	{
		return $this->post->findOrFail($id)->videos()->detach($video_ids);
	}

	public function addImages($post_id, $images)
	{
		$post = $this->post->findOrFail($post_id);
		return $result = $post->images()->saveMany($images);
	}

	public function addVideos($post_id, $videos)
	{
		return $this->post->findOrFail($post_id)->videos()->saveMany($videos);
	}

	public function updateCoverImage($post_id, $image)
	{
		return $this->post->findOrFail($post_id)->coverImage()->attach($image);

	}

	public function getBlendedPosts($input = array(), $parent_id)
	{
		$posts = $this->post->published();

		$posts = $posts->whereHas('section', function($q) use ($parent_id) {
        		return  $q->where('parent_id','=',$parent_id);
        	});

		$posts = $posts->orderBy('publish_date','desc');

		$limit = (isset($input['limit']) and ! empty($input['limit'])) ? $input['limit'] : Config::get('api.limit');
		$limit = $this->checkLimit($limit);

        return $posts->paginate($limit);

	}

	public function updateSection($id, $section_id)
	{
		$section = $this->sections->find($section_id);
		$post = $this->find($id);
		$post_section = $post->section;
		$post_section->posts()->detach($post->id);
		return $section->posts()->attach($post->id);

	}

	public function nearestScheduledPost()
	{
		return $this->post->nearestScheduledPost();
	}

	public function getFromMultipleSections($input = array(), $section_ids)
	{
		$posts = $this->post->published();

		$posts = $posts->whereHas('section', function($q) use ($section_ids) {
			return $q->whereIn('id',$section_ids);
		});

		$posts = $posts->orderBy('publish_date','desc');


		$limit = (isset($input['limit']) and ! empty($input['limit'])) ? $input['limit'] : Config::get('api.limit');
		$limit = $this->checkLimit($limit);

        return $posts->paginate($limit);

	}


	public function checkLimit($limit)
	{
		if($limit > Config::get('api.limit') )
		{
			return Config::get('api.limit');
		}

		return $limit;
	}


	public function getComments($id, $page)
	{
		$post = $this->find($id);
		return $post->comments()->orderBy('created_at', 'desc')->paginate('25',$page);
	}
}
