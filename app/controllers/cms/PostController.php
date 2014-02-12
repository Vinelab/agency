<?php namespace Agency\Cms\Controllers;

use Agency\Cms\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;

use Agency\Cms\Validators\Contracts\PostValidatorInterface;

use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\ImageRepositoryInterface;
use Agency\Cms\Repositories\Contracts\VideoRepositoryInterface;

use Agency\Media\Photos\UploadedPhoto;
use Agency\Media\Photos\UploadedPhotosCollection;
use Agency\Media\Photos\Contracts\ManagerInterface;

use Agency\Cms\Post;



use View,Input,App,Session,Auth,Response;

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
    							PostValidatorInterface $postValidator)
    {
        parent::__construct($sections);

		$this->sectionValidator = $sectionValidator;
		$this->post             = $post;
		$this->manager          = $manager;
		$this->image            = $image;
		$this->video            = $video;
		$this->postValidator    = $postValidator;
    }

	public function index()
	{
		return View::make('cms.pages.post.index', compact('permissions'));

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
			return View::make("cms.pages.post.create",compact("edit_post"));
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
				$post = $this->post->create(Input::get("title"),Input::get("body"),Auth::user()->id);

				if(isset($input['croped_images_array']))
				{
					$photos = new UploadedPhotosCollection;

				 	$crop_sizes = json_decode($input['croped_images_array']);


				 	if(isset($input['images']))
				 	{
				 		$images = $input['images'];

				 		foreach ($images as $key=>$image) {

							$crop_size = get_object_vars($crop_sizes[$key]);
						 	$photo = UploadedPhoto::make($image, $crop_size)->validate();
		        			$photos->push($photo);
						}

						$aws_response = $this->manager->upload($photos,'artists/webs');

						return dd($aws_response);


						for ($i=0 ; $i < sizeof($crop_sizes) ; $i++ ) { 
							$image = $this->image->create($aws_response[$i*4]->get('ObjectURL'));
							$this->image->deleteTemp($crop_sizes[$i]->name);
							$image->post()->create(["post_id"=>$post->id]);
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
				//display error

			}

		}

		throw new UnauthorizedException;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}