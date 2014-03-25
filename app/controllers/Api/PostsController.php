<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;
use Agency\Api\Repositories\Contracts\CodeRepositoryInterface;

use Input, Response, File, DB, Lang, Controller;

use Agency\Cms\Post;
use Agency\Section;

use Agency\Api\Api;

use Agency\Api\Mappers\PostMapper;
use Agency\Api\PostsCollection;

class PostsController extends Controller {

    public function __construct( PostRepositoryInterface $post,
    							 SectionRepositoryInterface $section,
    							 TagRepositoryInterface $tag,
                                 CodeRepositoryInterface $code)
    {
        $this->post = $post;
        $this->section = $section;
        $this->tag = $tag;
        $this->post_mapper = new PostMapper();
        $this->code = $code;
    }

    public function index()
    {
        if($this->code->findBy("code",Input::get('code')))
        {
            $posts = $this->post->allPublished(Input::all());
       
            if(get_class($posts)=='Illuminate\Pagination\Paginator')
            {
                $paginated_posts = $posts;
                $posts = $this->post_mapper->make($posts);

                return Api::respond($posts->toArray(),$paginated_posts->count(), $paginated_posts->getCurrentPage());

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
                $post = $this->post_mapper->make($post);
                return Api::respond($post,1,1);
            } else {
                return Response::json(['status'=>'400','message'=>Lang::get('api/posts.not_found')]);
            }
        } else {
            return Response::json(['status'=>400,'messages'=>Lang::get("messages.invalid_code")]);
        }
    }
}
