<?php namespace Agency\Http\Controllers\Cms;



use View,Input,App,Session,Auth,Redirect,Clockwork,Response,Config;
use Agency\Contracts\Repositories\PostRepositoryInterface;
use Agency\Api\Mappers\PostMapper;
use Agency\Contracts\Api\ApiInterface;
use Agency\Cache\PostCacheManager;



class SearchController extends Controller {

    public function __construct(PostRepositoryInterface $posts,
    	                        ApiInterface $api,
    	                        PostCacheManager $cache)
    {
    	$this->posts = $posts;
    	$this->post_mapper = new PostMapper();
    	$this->api = $api;
    	$this->cache = $cache;

    }

    public function index()
    {


        if(Auth::hasPermission("read"))
        {
            if(Input::has('keyword'))
            {
                $input = Input::all();
                $input['limit'] =25;
                $posts = $this->posts->paginatedPublishedPost($input);

                return View::make("cms.pages.content.search",["posts" => $posts]);

            } else {
                return Redirect::back();

            }
        }

        throw new UnauthorizedException;
    }


}
