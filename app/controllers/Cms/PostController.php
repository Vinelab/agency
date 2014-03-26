<?php namespace Agency\Cms\Controllers;

use Agency\Cms\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;

use Agency\Cms\Validators\Contracts\PostValidatorInterface;
use Agency\Cms\Validators\Contracts\TagValidatorInterface;

use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\ImageRepositoryInterface;
use Agency\Cms\Repositories\Contracts\VideoRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;

use Agency\Media\Photos\UploadedPhoto;
use Agency\Media\Photos\UploadedPhotosCollection;
use Agency\Media\Photos\Contracts\ManagerInterface;

use Agency\Cms\Post;

use Agency\Cms\Tag;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Agency\Helper;


use View,Input,App,Session,Auth,Response,Redirect;

class PostController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	/**
     * The section validator instance.
     *
     * @var Agency\Cms\Validators\SectionValidator
     */
    protected $sectionValidator;

    public function __construct(SectionRepositoryInterface $sections,
    							SectionValidator $sectionValidator,
    							PostRepositoryInterface $post,
    							ImageRepositoryInterface $image,
    							ManagerInterface $manager,
    							VideoRepositoryInterface $video,
    							TagRepositoryInterface $tag,
    							TagValidatorInterface $tagValidator,
    							PostValidatorInterface $postValidator)
    {
        parent::__construct($sections);

		$this->sectionValidator = $sectionValidator;
		$this->post             = $post;
		$this->manager          = $manager;
		$this->image            = $image;
		$this->video            = $video;
		$this->postValidator    = $postValidator;
		$this->section          = $sections;
		$this->tag 				= $tag;
		$this->tagValidator     = $tagValidator;
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

		if($this->admin_permissions->has("create"))
		{

			$edit_post=null;

			$contents = $this->section->infertile();

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
		if($this->admin_permissions->has("create"))
		{
			$input = Input::all();

			if($this->postValidator->validate($input))
			{

			  	$slug = $this->post->uniqSlug( Helper::slugify(Input::get('title')) );
				$body = Helper::cleanHtml(Input::get('body'));
				
				$section = $this->section->findBy('alias',Input::get('section'));

				$post = $this->post->create(Input::get('title'),$body,Auth::user()->id,$section->id,Input::get('publish_date'),Input::get('publish_state'),$slug);

				$tags = input::get('tags');
				if($tags!="")
				{
					$tags = explode(", ", $tags);
					array_map(function($tag)use($post){

						$result = $this->tag->create($tag);
						$post->tags()->save($result);
					}, $tags);
				}

				if(isset($input['croped_images_array']))
				{
					$photos = new UploadedPhotosCollection;

				 	$crop_sizes = json_decode($input['croped_images_array']);

				 	if(isset($input['images']))
				 	{
				 		$images = $input['images'];

				 		foreach ($images as $key=>$image) {
				 			$image= new UploadedFile(public_path()."/tmp/$image",$image);
							$crop_size = get_object_vars($crop_sizes[$key]);
						 	$photo = UploadedPhoto::make($image, $crop_size)->validate();
		        			$photos->push($photo);

						}

						$aws_response = $this->manager->upload($photos,'artists/webs');

						$aws_response = $aws_response->toArray();
						foreach ($aws_response as $response) {
							$image = $this->image->create($response);

							$image->post()->create(["post_id"=>$post->id]);
						}

						for ($i=0 ; $i < sizeof($crop_sizes) ; $i++ ) { 
							$this->image->deleteTemp($crop_sizes[$i]->name);
						}

				 	}
				}

				if(isset($input["videos"]))
				{
					$videos = json_decode($input["videos"]);

					foreach ($videos as $video) {
						if($this->video->validate_url($video->url))
						{
							$v = $this->video->create($video->url,$video->title,$video->desc,$video->src);
							$v->post()->create(["post_id"=>$post->id]);

						}
					}
				}
				return Response::json($post);

			} else {
				return Response::json(['status'=>400,"message"=>$this->postValidator->messages()]);
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
		if($this->admin_permissions->has("read"))
		{
			try {

				$post = $this->post->findBy("slug",$slug);

				$section = $post->section()->first();

				//get all parent sections
				$parent_sections = $this->section->parentSection($section);

				$gallery = $post->media()->get();
				
				$media=[];
				foreach ($gallery as $value) {
					array_push($media, $value->media);					
				}
				
				$tags = $post->tags()->get();

				return View::make('cms.pages.post.show',compact('post','media','parent_sections','tags'));

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
		if($this->admin_permissions->has("update"))
		{

			try {

				$post=$this->post->findBy("slug",$slug);
				$media = $post->media()->get();
				$media_array=[];
				foreach ($media as $value) {
					array_push($media_array, $value->media);
				}

				$tags = $post->tags()->get()->fetch('text')->toArray();

				$contents = $this->section->infertile();
				return View::make("cms.pages.post.edit",["edit_post"=>$post,'contents'=>$contents,'tags'=>$tags,'media'=>$media_array]);

				
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
		if($this->admin_permissions->has("update"))
		{
			$input = Input::all();

			if($this->postValidator->validate($input))
			{
				$body = Helper::cleanHtml(Input::get('body'));
				$slug = $this->post->uniqSlug( Helper::slugify(Input::get('title')) );

				$section = $this->section->findBy('alias',Input::get('section'));

				$post = $this->post->update($id,Input::get("title"),$body,Auth::user()->id,$section->id,Input::get('publish_date'),Input::get('publish_state'),$slug);
				$deleted_images = Input::get('deleted_images');
				if($deleted_images!="")
				{
					$deleted_images = explode(",", $deleted_images);
					foreach ($deleted_images as $image) {
						$this->removePhoto($post,$image);
					}
				}
	

				$tags = Input::get('tags');
				$tags = explode(", ", $tags);
				if(!empty($post->tags()->get()))
				{
					$post->tags()->detach();
				}

				//remove empty string from tags array
				$tags = array_filter($tags);

				array_map(function($tag)use($post){
					
					$result = $this->tag->create($tag);
					$post->tags()->save($result);
					
				}, $tags);


				if(isset($input['croped_images_array']))
				{
					$photos = new UploadedPhotosCollection;

				 	$crop_sizes = json_decode($input['croped_images_array']);

				 	if(isset($input['images']))
				 	{
				 		$images = $input['images'];

				 		foreach ($images as $key=>$image) {
				 			$image= new UploadedFile(public_path()."/tmp/$image",$image);
							$crop_size = get_object_vars($crop_sizes[$key]);
						 	$photo = UploadedPhoto::make($image, $crop_size)->validate();
		        			$photos->push($photo);

						}

						$aws_response = $this->manager->upload($photos,'artists/webs');

						$aws_response = $aws_response->toArray();

						foreach ($aws_response as $response) {

							$image = $this->image->create($response);

							$image->post()->create(["post_id"=>$post->id]);
						}

						for ($i=0 ; $i < sizeof($crop_sizes) ; $i++ ) { 
							$this->image->deleteTemp($crop_sizes[$i]->name);
						}

				 	}
				}


				
					$videos = json_decode(Input::get('videos'));

					$this->video->detachAll($post);

					


					foreach ($videos as $video) {
						if($this->video->validate_url($video->url))
						{
							$v = $this->video->create($video->url,$video->title,$video->desc,$video->src);
							$v->post()->create(["post_id"=>$post->id]);
						}
					}
			
				return Response::json($post);
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
		if($this->admin_permissions->has("delete"))
		{
			try {
				$section=$this->post->section($slug);
				if($this->post->delete($slug))
					return Redirect::route("cms.content.show",$section->alias);	
			} catch (Exception $e) {
				return Response::json(['message'=>$e->getMessage()]);
			}
			
		}

		throw new UnauthorizedException;
	}

	public function removePhoto($post, $photo_id)
	{
		try {

			$this->image->detachImageFromPost($photo_id,$post);
			$image=$this->image->delete($photo_id);

		} catch (Exception $e) {
			return Response::json(['messages'=>$e->getMessages()]);
		}
		
	}




}