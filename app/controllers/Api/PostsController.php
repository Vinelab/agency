<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;

use Input, Response, File, DB, Lang;

use Agency\Cms\Post;
use Agency\Cms\Section;

use Agency\Api\Api;

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
        $posts = $this->post->allPublished(Input::all());
        return Api::respond($posts->toArray(),$posts->total(), $posts->page());
    }


    public function show($idOrSlug)
    {
        $post = $this->post->findByIdOrSlug($idOrSlug);
        if(!is_null($post))
        {
            $post = $this->postMapper->make($post);
            return Api::respond($post,1,1);
        } else {
            return Response::json(['status'=>'400','message'=>Lang::get('api/posts.not_found')]);
        }
    }
}
