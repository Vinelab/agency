<?php namespace Agency\Api\Controllers;

use Agency\Contracts\Repositories\PostRepositoryInterface;
use Agency\Contracts\Cms\Repositories\SectionRepositoryInterface;
use Agency\Contracts\Repositories\TagRepositoryInterface;
use Agency\Contracts\Repositories\CodeRepositoryInterface;

use Agency\Cache\PostCacheManager;

use Input, Response, File, DB, Lang, Controller;

use Agency\Api\Exceptions\InvalidCodeException;
use Agency\Post;
use Agency\Cms\Section;

use Agency\Contracts\Api\ApiInterface;

use Agency\Api\Mappers\PostMapper;
use Agency\Api\PostsCollection;
use Agency\Api\Validators\CodeValidator;
use Exception;
use Agency\Exceptions\PostNotFoundException;

use Agency\Exceptions\InvalidSectionException;

use Paginator, Cache;

use Carbon\Carbon;

class PostsController extends Controller {

    public function __construct(PostCacheManager $cache,
                                SectionRepositoryInterface $section,
                                PostRepositoryInterface $post,
                                ApiInterface $api)
    {

        $this->cache = $cache;
        $this->section = $section;
        $this->post = $post;
        $this->post_mapper = new PostMapper();
        $this->api = $api;
    }

    public function index()
    {

        try {

            $key='all'.'-'.Input::get('limit').'-'.Input::get('page').'-'.Input::get('tag').'-'.Input::get('category').'-'.Input::get('featured').'-'.Input::get('keyword');
            $posts = $this->cache->remember($key, function(){

                $input = Input::all();

                $section = $this->getSection();

                $posts = $this->getPosts($section);
                
                $total = $posts->getTotal();

                $posts = $this->post_mapper->make($posts);

                return $this->api->respond($posts, $total, Input::get('page'));

            },['posts'],$this->getNearestPostDuration($key));

            return $posts;

        } catch (InvalidCodeException $e) {
            return Response::json(['status'=> 400, 'message'=> Lang::get('messages.invalid_code')]);
        }
        catch (Exception $e) {
            return Response::json(['status'=> 400, 'message'=>Lang::get('messages.something_wrong')]);
        }
    }


    public function show($idOrSlug)
    {
        try
        {
            $post = $this->cache->remember($idOrSlug, function() use ($idOrSlug){
                $post =  $this->post->findByIdOrSlug($idOrSlug);
                $post = $this->post_mapper->make($post);
                return $this->api->respond($post,1,1);
            },['posts']);

            return $post;

        } catch (Exception $e) {
            return Response::json(['status'=> 400, 'message'=>Lang::get('messages.something_wrong')]);
        } catch (InvalidCodeException $e) {
            return Response::json(['status'=> 400,'messages'=>Lang::get("messages.invalid_code")]);
        } catch (PostNotFoundException $e)
        {
            return Response::json(['status'=> 400,'messages'=>Lang::get("api/posts.not_found")]);
        }

    }


    public function getNearestPostDuration($key)
    {
        if($duration = $this->cache->get($key.'-duration'))
        {
            return $duration;
        } else {

             $nearest_scheduled_post = $this->post->nearestScheduledPost();

            if(! is_null($nearest_scheduled_post))
            {
                $nearest_scheduled_posts_date = new Carbon($nearest_scheduled_post->publish_date,'Asia/Beirut');

                $time_difference = $nearest_scheduled_posts_date->diffInMinutes(Carbon::now('Asia/Beirut'))+1;

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


    public function getSection()
    {
        if(!is_null(Input::get('category')))
        {
            $categories = explode(',', Input::get('category'));

            $section = $this->section->getMultipleSectionBySlug($categories,'children');

            if(!is_null($section))
            {
                return $section;
            } else {

                throw new Exception( Lang::get('messages.invalid_category'), 1);

            }
        }

    }

    public function getPosts($sections)
    {
        if(sizeof($sections)>1)
        {
            $section_ids = $this->section->getIdsofMutlipleSections($sections);

            return $this->post->getFromMultipleSections(Input::all(), $section_ids);

        } else {

            if($sections instanceOf \Illuminate\Database\Eloquent\Collection )
            {
                $section = $sections->first();
            }
            
            if(isset($section->filter_enable) AND ($section->filter_enable == false) )
            {

                return $this->post->getBlendedPosts(Input::all(), $section->id);

            } else {

                return $this->post->paginatedPublishedPost(Input::all());
            }

        }

    }

}
