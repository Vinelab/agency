<?php namespace Agency\Office\Controllers;

use Agency\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;


use Agency\Contracts\Office\Repositories\SectionRepositoryInterface;
use Agency\Repositories\Contracts\PostRepositoryInterface;

use Agency\Helper;



use View,Input,App,Session,Auth,Redirect,Clockwork,Response,Config;
use Which;

class ContentController extends Controller {

	/**
     * The section validator instance.
     *
     * @var Agency\Validators\SectionValidator
     */
    protected $sectionValidator;

    protected $result;



    public function __construct(SectionRepositoryInterface $section)
    {
     	$this->sections = $section;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('cms.pages.content.home');
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

			$section = $this->sections->findBy("alias",$alias);

			//get parent section

			if(!is_null($section))
			{
				if($section->is_fertile)
				{
					//get its children
					$section_posts=[];
					$sub_sections = $section->children;

					return View::make("cms.pages.content.index", [
						"sub_sections"    => $sub_sections,
					]);

				} else {

						$posts="";

						if($section->posts->count()>0)
						{
							$posts = $this->sections->getRelatedPosts($section->id);
						}

						return View::make("cms.pages.content.posts",[
							"posts" => $posts,
						]);
					}
			}

		} catch (Exception $e) {
			return Response::json(["message"=>$e->getMessage()]);
		}	
	}
}




	
	
	



