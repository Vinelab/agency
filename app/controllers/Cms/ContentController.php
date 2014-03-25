<?php namespace Agency\Cms\Controllers;

use Agency\Cms\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;


use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;

use Agency\Helper;



use View,Input,App,Session,Auth,Redirect,Clockwork,Response,Config;

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
    							PostRepositoryInterface $post)
    {
        parent::__construct($sections);

		$this->sectionValidator = $sectionValidator;
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($alias)
	{
		try {
			$section = $this->section->findBy("alias",$alias);

			//get parent section
			$parent_sections = $this->section->parentSection($section);

			if(!is_null($section))
			{

				$this->section->set($section);
				//check if section is fertile
				if($section->is_fertile)
				{
					//get its children
					$section_posts=[];
					$sub_sections = $this->section->children();

					return View::make("cms.pages.content.index",compact("sub_sections","parent_sections"));
				
				} else {

						$posts="";
						if($section->posts()->count()>0)
						{

							$posts = $section->posts()->latest('created_at')->paginate(Config::get('posts.number_per_page'));
						}

						return View::make("cms.pages.content.posts",compact("posts","parent_sections"));	
					}
			}

		} catch (Exception $e) {
			return Response::json(["message"=>$e->getMessage()]);
		}	
	}






	
	
	



}