<?php namespace Agency\Cms\Controllers;

use Agency\Cms\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;


use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;

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
					foreach ($sub_sections as $key => $sub_section) {
						$posts="";

							if($sub_section->posts()->count()>0)
							{
								$posts_id = $sub_section->posts()->get(['id'])->fetch('id')->toArray();
								$posts = $this->post->getPostsByIds($posts_id);
							}
													
						array_push($section_posts,['sub_section'=>$sub_section,'posts'=>$posts]);
					}
					return View::make("cms.pages.content.index",compact("section_posts","parent_sections"));
				}else{
					
						$posts="";
						if($section->posts()->count()>0)
						{
							$posts_id = $section->posts()->get(['id'])->fetch('id')->toArray();
							$posts = $this->post->getPostsByIds($posts_id);
						}

						return View::make("cms.pages.content.posts",compact("posts","parent_sections"));
					
				}
			}

			//check if the section is fertile
			//if yes get its children
			//if not get its content
		} catch (Exception $e) {
			return Response::json(["message"=>$e->getMessage()]);
		}	
	}






	
	
	



}