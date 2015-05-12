<?php namespace Agency\Http\Controllers\Api;



use Input, Response, File, DB, Lang;
use Agency\Contracts\Repositories\PostRepositoryInterface;
use Agency\Api\Mappers\PostMapper;
use Agency\Contracts\Api\ApiInterface;
use Agency\Cache\PostCacheManager;
use Agency\Http\Controllers\Controller;


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
    	if(Input::has('keyword'))
    	{
    		$key = 'search'.'-'.Input::get('keyword').'-'.Input::get('page');
    		$posts = $this->cache->remember($key, function(){
	    		$input = Input::all();
		    	$input['limit'] =25;
		    	$posts = $this->posts->paginatedPublishedPost($input);
		    	$total = $posts->getTotal();

		        $posts = $this->post_mapper->make($posts);

		        return $this->api->respond($posts, $total, Input::get('page'));
    		},['posts'],$this->getNearestPostDuration($key));

    		return $posts;
    	}


    	return Response::json(['status'=> 400,'messages'=>Lang::get("api/posts.search_error")]);



    }

    public function getNearestPostDuration($key)
    {
        if($duration = $this->cache->get($key.'-duration'))
        {
            return $duration;
        } else {

             $nearest_scheduled_post = $this->posts->nearestScheduledPost();

            if(! is_null($nearest_scheduled_post))
            {
                $nearest_scheduled_posts_date = new Carbon($nearest_scheduled_post->publish_date,config('app.timezone'));

                $time_difference = $nearest_scheduled_posts_date->diffInMinutes(Carbon::now(config('app.timezone')))+1;

                if($time_difference > 0)
                {
                     $duration = $this->cache->remember($key.'-duration',function() use ($time_difference) {
                        return $time_difference;
                    },['posts'],$time_difference);

                    return $duration;
                }

            }
        }
    }
}
