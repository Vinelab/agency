<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;

use Input, Response, File, DB, Lang;

use Agency\Cms\Post;
use Agency\Cms\Section;

use Agency\Api\Mappers\PostMapper;
use Agency\Api\PostsCollection;

class PostsController extends \Controller {

    public function __construct( PostRepositoryInterface $post,
    							 SectionRepositoryInterface $section,
    							 TagRepositoryInterface $tag)
    {
        $this->post = $post;
        $this->section = $section;
        $this->tag = $tag;
        $this->postMapper = new PostMapper();
        $this->postsCollection = new PostsCollection();
    }

    public function index()
    {
        $posts = $this->post->allPublished();

        if(Input::has('category'))
        {   
            $section = $this->section->findBy('alias',Input::get('category'));
            $posts = $this->post->fromSection($posts,$section);

            // $posts = $posts->join('cms_sections', 'cms_sections.id','=','posts.section_id')->where('alias','=',Input::get('category'));
            // return dd($posts->first());
        }

        if(Input::has('tag'))
        {
            $posts=$posts->whereHas('tags',function($q){
                return $q->where('slug','=',Input::get('tag'));
            });
        }

    	if (Input::has('limit'))
    	{
            $posts = $posts->paginate((int)Input::get('limit'));
    	}else{

            $posts = $posts->get();
        }



        return dd($this->postMapper->make($posts)->toArray());

    }


    public function show($idOrSlug)
    {
        $post = $this->post->findByIdOrSlug($idOrSlug);
        if(!is_null($post))
        {
            $this->postsCollection->push($post);
            return dd($this->postMapper->make($this->postsCollection)->first());
        } else {
            return Response::json(['status'=>'400','message'=>Lang::get('api/posts.not_found')]);
        }
    }
}
