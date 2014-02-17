<?php namespace Agency\Cms\Controllers;

use Agency\Cms\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;
use Agency\Cms\Validators\Contracts\ContentValidatorInterface;


use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\ContentRepositoryInterface;
use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;


use Agency\Cms\Content;
use Agency\Helper;



use View,Input,App,Session,Auth,Redirect,Clockwork,Response;

class ContentController extends Controller {

	/**
     * The section validator instance.
     *
     * @var Agency\Cms\Validators\SectionValidator
     */
    protected $sectionValidator;

    protected $result;



    public function __construct(SectionRepositoryInterface $sections,
    							SectionValidator $sectionValidator,
    							ContentRepositoryInterface $content,
    							PostRepositoryInterface $post,
    							ContentValidatorInterface $contentValidator)
    {
        parent::__construct($sections);

		$this->sectionValidator = $sectionValidator;
		$this->contentValidator = $contentValidator;
		$this->content             = $content;
		$this->post = $post;
		$this->result=[];
		$this->section = $sections;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		if($this->admin_permissions->has("create"))
		{
			$content = $this->section->findBy("alias","content");
			$this->section->set($content);
			$sections = $this->section->children();

			return View::make('cms.pages.content.home', compact('sections'));
		}

		throw new UnauthorizedException;
		
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
			$contents = $this->content->all();
			return View::make("cms.pages.content.create",compact("contents"));
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
		$input = Input::all();
		if($this->contentValidator->validate($input))
		{
			$alias = Helper::aliasify($input["title"]);

			$content = $this->content->create($input["title"],$alias,$input["parent_id"]);
			return Redirect::route("cms.content");
		}else{
			//return error
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($alias)
	{
		try {
			$section = $this->section->findBy("alias",$alias);

			if(!is_null($section))
			{
				$this->section->set($section);
				//check if section is fertile
				if($section->is_fertile)
				{
					//get its children
					$section_posts=[];
					$sub_sections = $this->section->children();
					foreach ($sub_sections as $key => $sub_section) {
						$posts="";

							if($sub_section->posts()->count()>0)
							{
								$posts_id = $sub_section->posts()->get(['id'])->fetch('id')->toArray();
								$posts = $this->post->getPostsByIds($posts_id);
							}
							
						
						
						array_push($section_posts,['sub_section'=>$sub_section,'posts'=>$posts]);
					}

					return View::make("cms.pages.content.index",compact("section_posts"));
				}else{
					
						$posts="";
						if($section->posts()->count()>0)
						{
							$posts_id = $section->posts()->get(['id'])->fetch('id')->toArray();
							$posts = $this->post->getPostsByIds($posts_id);
						}

						return View::make("cms.pages.content.posts",compact("posts"));
					
				}
			}

			//check if the section is fertile
			//if yes get its children
			//if not get its content
		} catch (Exception $e) {
			return Response::json(["message"=>$e->getMessage()]);
		}
		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if($this->admin_permissions->has("update"))
		{
			$content = $this->content->findBy("id",$id);
			if($content!=false)
			{	
				$content = $content->get()->first();
				$contents = $this->content->all();

				return View::make("cms.pages.content.create",compact("content","contents"));
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
			try{
				if($this->content->update($id,Input::get("title"),Input::get("parent_id")))
					return Redirect::route("cms.content");

			} catch (Exception $e) {
				return Response::json(["message"=>$e->getMessage()]);
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
	public function destroy($id)
	{
		if($this->admin_permissions->has("delete"))
		{
			$children = $this->content->findBy("parent_id",$id);
			if($children!=false)
			{

				foreach ($children->get() as $key => $child) {
					
					$this->content->delete($child->id);
				}

			} 

			if($this->content->delete($id))
				return Redirect::route("cms.content");	
		}

		throw new UnauthorizedException;
	}


	public function isParent($content)
	{
		$children = $this->content->findBy("parent_id",$content->id);
		if($children==false)
		{
			array_push($this->result,["children"=>$content->title,"parent_id"=>$content->parent_id,"id"=>$content->id]);

		} else {
			$children = $children->get();
			array_push($this->result, ["parent"=>$content->title,"parent_id"=>$content->parent_id,"id"=>$content->id]);

			foreach ($children as $key => $child) {
				$this->isParent($child);
			}
		}
	}

	public function assign()
	{

		if($this->admin_permissions->has("create"))
		{
			$post_id="";
			$content_id="";

			if(isset($_GET['post']))
			{
				$post_id=$_GET['post'];
			}

			if(isset($_GET['content']))
			{
				$content_id=$_GET['content'];
			}

			$edit_section = null;
			$contents = $this->section->infertile();

			$posts=$this->post->all();
			return View::make("cms.pages.content.assign",compact("edit_section","contents","posts","post_id","content_id"));
		}

		throw new UnauthorizedException;
	}

	
	public function section($id)
	{
		try {
			$content = $this->content->find($id);
			$posts = $content->linker->lists("post_id");

			if(!empty($posts))
			{
				$posts = $this->post->getPostsByIds($posts);
				return $posts;
			}

			return Redirect::route("cms.content");
			
		} catch (Exception $e) {
			return Response::json(["message"=>$e->getMessage()]);
		}
		
	}


}