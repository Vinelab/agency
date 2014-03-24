<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;
use Agency\Api\Repositories\Contracts\CodeRepositoryInterface;

use Input, Response, File, DB, Lang;

use Agency\Cms\Post;
use Agency\Cms\Section;

use Agency\Api\Api;

use Agency\Api\Mappers\PostMapper;
use Agency\Api\PostsCollection;

class PostsController extends \Controller {

    public function __construct( PostRepositoryInterface $post,
    							 SectionRepositoryInterface $section,
    							 TagRepositoryInterface $tag,
                                 CodeRepositoryInterface $code)
    {
        $this->post = $post;
        $this->section = $section;
        $this->tag = $tag;
        $this->postMapper = new PostMapper();
        $this->postsCollection = new PostsCollection();
        $this->code = $code;
    }

    public function index()
    {
        if($this->code->findBy("code",Input::get('code')))
        {
            $posts = $this->post->allPublished(Input::all());
            if(get_class($posts)=='Agency\Api\PostsCollection')
            {
                return Api::respond($posts->toArray(),$posts->total(), $posts->page());
            } else {
                return $posts;
            }
        } else {
            return Response::json(['status'=>400,'messages'=>Lang::get("messages.invalid_code")]);
        }
        
    }


    public function show($idOrSlug)
    {
        if($this->code->findBy("code",Input::get('code')))
        {   
            $post = $this->post->findByIdOrSlug($idOrSlug);
            if(!is_null($post))
            {
                $post = $this->postMapper->make($post);
                return Api::respond($post,1,1);
            } else {
                return Response::json(['status'=>'400','message'=>Lang::get('api/posts.not_found')]);
            }
        } else {
            return Response::json(['status'=>400,'messages'=>Lang::get("messages.invalid_code")]);
        }
    }
}
