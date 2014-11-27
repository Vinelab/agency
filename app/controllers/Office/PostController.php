<?php namespace Agency\Office\Controllers;

use Agency\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;

use Agency\Contracts\Validators\PostValidatorInterface;
use Agency\Validators\Contracts\TagValidatorInterface;

use Agency\Contracts\Office\Repositories\SectionRepositoryInterface;
use Agency\Contracts\Repositories\PostRepositoryInterface;
use Agency\Repositories\Contracts\VideoRepositoryInterface;
use Agency\Contracts\Repositories\TagRepositoryInterface;

use Agency\Media\Photos\UploadedPhoto;
use Agency\Media\Photos\UploadedPhotosCollection;
use Agency\Media\Photos\Contracts\ManagerInterface;
use Agency\Media\Photos\Contracts\StoreInterface;
use Agency\Media\Photos\Contracts\FilterResponseInterface;
use Agency\Media\Videos\Contracts\ParserInterface;

use Agency\Post;
use Which;
use Config;
use Agency\Cms\Tag;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Agency\Contracts\HelperInterface;


use View,Input,App,Session,Auth,Response,Redirect;

class PostController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	

    public function __construct(PostValidatorInterface $validator,
    							PostRepositoryInterface $post,
    							HelperInterface $helper,
    							SectionRepositoryInterface $section,
    							ParserInterface $parser_interface,
    							TagRepositoryInterface $tag,
    							ManagerInterface $manager,
    							FilterResponseInterface $filter_response)
    {
		$this->validator = $validator;
		$this->posts = $post;
		$this->helper = $helper;
		$this->sections = $section;
		$this->parser_interface = $parser_interface;
		$this->tags = $tag;
		$this->manager = $manager;
		$this->filter_response = $filter_response;        
    }

	public function index()
	{
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		if(Auth::hasPermission('create'))
		{

			$edit_post=null;

			$contents=Which::children();
			
			return View::make("cms.pages.post.create",compact("edit_post",'contents'));
		}

		throw new UnauthorizedException;

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Auth::hasPermission("create"))
		{
			if($this->validator->validate(Input::all()))
			{
			  	$slug = $this->posts->uniqSlug( Input::get('title') );
				$body = $this->helper->cleanHtml(Input::get('body'));

				$section = $this->sections->findBy('alias',Input::get('section'));

				$related_models = $this->save();

				$related_models['section'] = $section;

				$publish_state = $this->filterPublishState(Input::get('publish_state'));
				$publish_date = Input::get('publish_date');
				$publish_date = $this->formatDate($publish_date);
				$post = $this->posts->createWith(Input::get('title'), $slug, $body,  Input::get('featured') ,$publish_date, $publish_state, $related_models);

				$this->posts->updateSection($post->id, $section->id);

				// $this->cache->forgetByTags(['posts']);

				return Response::json($post);

			} else {
				return Response::json(['status'=>400,"message"=>$this->validator->messages()]);
			}
		}

		throw new UnauthorizedException;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $slug
	 * @return Response
	 */
	public function show($slug)
	{
		if(Auth::hasPermission("read"))
		{
			try {

				$post = $this->posts->findBy("slug",$slug);
				$section = $post->section()->first();

				//get all parent sections

				$images = $post->images;

				$videos = $post->videos;

				$tags = $post->tags;


				return View::make('cms.pages.post.show',[
					'tags'   => $tags,
					'post'   => $post,
					'images' => $images,
					'videos' => $videos,
				]);

			} catch (Exception $e) {
				return Response::json(['message'=>$e->getMessage()]);
			}

		}

		throw new UnauthorizedException;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $slug
	 * @return Response
	 */
	public function edit($slug)
	{
		if(Auth::hasPermission("update"))
		{

			try {

				$post=$this->posts->findBy("slug",$slug);

				$images = $post->images;

				$videos = $post->videos;

				$tags = $post->tags->lists('text');

				$contents=Which::children();

				return View::make("cms.pages.post.edit",["edit_post"=>$post,'contents'=>$contents,'tags'=>$tags,'images'=>$images, 'videos'=>$videos]);

			} catch (Exception $e) {
				return Response::json(['message'=>$e->getMessage()]);
			}

		}

		throw new UnauthorizedException;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(Auth::hasPermission("update"))
		{
			if($this->validator->validate(Input::all()))
			{

				$body = $this->helper->cleanHtml(Input::get('body'));

				$post = $this->posts->find($id);
				($post->title == Input::get('title')) ? $slug = $post->slug : $slug = $this->posts->uniqSlug(Input::get('title'));
				$section = $this->sections->findBy('alias', Input::get('section'));

				$publish_state = $this->filterPublishState(Input::get('publish_state'));

				if($post->publish_state == $publish_state && $publish_state == 'published')
				{
					$publish_date = $post->publish_date;
				} else {

					$publish_date = Input::get('publish_date');
					$publish_date = $this->formatDate($publish_date);
				}

				if ($section)
				{

					$updated = $this->posts->update($id,
												Input::get("title"),
												$slug,
												$body,
												Input::get('featured'),
												$publish_date,
												$publish_state);


					if ($updated)
					{

						$this->posts->updateSection($id, $section->id);

						$deleted_images = Input::get('deleted_images');

						if( ! empty($deleted_images))
						{
							$deleted_images =array_map('intval', explode(',', $deleted_images));

							$this->posts->detachImages($id,$deleted_images);
							$this->images->remove($deleted_images);
						}

						$deleted_videos =Input::get('deleted_videos');

						if( ! empty($deleted_videos))
						{
							$deleted_videos = array_map('intval', explode(',', $deleted_videos));

							$result = $this->posts->detachVideos($id, $deleted_videos);
							$this->videos->remove($deleted_videos);
						}



						$related_models = $this->save($id);



						if( ! empty($related_models['tags']))
						{
							$tags = $related_models['tags'];
							$this->posts->addTags($id, $tags);
						}

						if(! empty($related_models['images']))
						{
							$images = $related_models['images'];
							$this->posts->addImages($id, $images);
						}

						if(! empty($related_models['videos']))
						{
							$videos = $related_models['videos'];
							$this->posts->addVideos($id, $videos);
						}

						if(! empty($related_models['coverImage']))
						{
							$image = $related_models['coverImage'];
							$this->posts->updateCoverImage($id, $image);
						}

						// $this->cache->forgetByTags(['posts']);

					}
				}

				return Response::json($updated);
			} else {
				//display error

			}

		}

		throw new UnauthorizedException;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($slug)
	{
		if(Auth::hasPermission("delete"))
		{
			try {

				$post = $this->posts->findByIdOrSlug($slug);

				$section = $post->section;


				if($this->posts->remove($post->id))
				{
					// $this->cache->forgetByTags(['posts']);

					return Redirect::route("cms.content.show",$section->alias);
				}

			} catch (Exception $e) {
				return Response::json(['message'=>$e->getMessage()]);
			}

			// $this->cache->forgetByTags(['posts']);


		}

		throw new UnauthorizedException;
	}

	public function removeImage($post_id, $image_id)
	{
		try {

			$this->posts->detachImage($post_id, $image_id);

			$image = $this->images->delete($image_id);

		} catch (Exception $e) {

			return Response::json(['messages' => $e->getMessages()]);
		}

	}

	public function save($post_id = null)
	{
		$related_models = [];

		$related_models['admin']=Auth::getUser();

		$tags = Input::get('tags');




		$tags_id = $this->addTags($tags, $post_id);

		if(! is_null($tags_id))
		{
			$related_models['tags'] = $tags_id;
		}


		$photos = new UploadedPhotosCollection;

		// upload images
		if(Input::has('croped_images_array'))
		{

		 	$crop_sizes = json_decode(Input::get('croped_images_array'));


		 	if(Input::has('images'))
		 	{
		 		$images = Input::get('images');

		 		foreach ($images as $key => $image) {

		 			$image = new UploadedFile(public_path()."/".Config::get("media.location")."/".$image, $image);
					$crop_size = get_object_vars($crop_sizes[$key]);

				 	$photo = UploadedPhoto::make($image, $crop_size)->validate();
        			$photos->push($photo);
				}

				$response = $this->manager->upload($photos,'artists/webs');


				$response = $response->toArray();

				$images = $this->filter_response->make($response);

				$related_models['images'] = $images['originals'];
		 	}
		}

		$photos = new UploadedPhotosCollection;


		if(Input::has('cover'))
		{
			$cover_crop_size = json_decode(Input::get('croped_cover'));
			if(! is_null($cover_crop_size))
			{
				$cover = Input::get('cover');
				$cover = new UploadedFile(public_path().$cover, $cover);
				$cover_crop_size = get_object_vars($cover_crop_size);

			 	$cover = UploadedPhoto::make($cover, $cover_crop_size)->validate();
				$photos->push($cover);

				$response = $this->manager->upload($photos,'artists/webs');
				$response = $response->toArray();
				$images = $this->filter_response->make($response);
				$related_models['coverImage'] = $images['originals'];
			}
		}

		$videos = json_decode(Input::get('videos'));

		if(sizeof($videos)>0)
		{
			$videos = $this->parser_interface->make($videos);

			$related_models['videos'] = $videos;
		}

		return $related_models;

	}

	public function addTags($tags, $post_id = null)
	{
		if(! is_null($post_id))
		{
			$this->posts->detachTags($post_id);
		}


		$tags = explode(', ', Input::get('tags'));
		// filter empty tags
		$tags = array_filter($tags);

		if(!empty($tags))
		{
			// get tags ids
			$tags = $this->tags->splitFound($tags);

			return $tags;
		}
	}


}