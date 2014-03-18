<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;

use Input, Response, File;

class PostsController extends \Controller {

    public function __construct( PostRepositoryInterface $post,
    							 SectionRepositoryInterface $section,
    							 TagRepositoryInterface $tag)
    {
        $this->post = $post;
        $this->section = $section;
        $this->tag = $tag;
    }

    public function index()
    {
    	$posts = $this->post->all();


    	if(isset($_GET['category']))
    	{
    		$category = $_GET['category'];
    		$section = $this->section->findBy('alias',$category);
    		$posts = $posts->filter(function($post)use($section){
    			if($post->section_id == $section->id)
    			{
    				return $post;
    			}
    		});
    	}

    	if(isset($_GET['tag']))
    	{
    		$tag = $_GET['tag'];
    		$tag = $this->tag->findBy('slug',$tag);
    		return dd($tag);

    	}

    	if(isset($_GET['limit']))
    	{
    		$limit = $_GET['limit'];
    	}

    	if(isset($_GET['page']))
    	{
    		$page = $_GET['page'];
    	}






    }
}
