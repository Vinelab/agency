<?php namespace Agency\Http\Controllers\Api;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Agency\Contracts\NewsRepositoryInterface;
use Agency\Contracts\NewsServiceInterface;
use Agency\Http\Controllers\Controller;
use Which;
use Api;

class NewsController extends Controller
{

    /**
     * news repository instance
     */
    protected $news;

    /**
     * @param \Agency\Contracts\NewsRepositoryInterface $news
     * @param \Agency\Contracts\NewsServiceInterface    $news_service
     */
    public function __construct(NewsRepositoryInterface $news, NewsServiceInterface $news_service)
    {
        $this->news = $news;
        $this->news_service = $news_service;
    }


    /**
     * @return mixed
     */
    public function index()
    {
        $category = Which::category();

        $news = $this->news_service->allPublished($category, Api::limit());

        return Api::respond('NewsMapper', $news);
    }


    /**
     * @param $id_or_slug
     *
     * @return mixed
     */
    public function show($id_or_slug)
    {
        try {

            $news = $this->news_service->findByIdOrSlug($id_or_slug);

        } catch (ModelNotFoundException $e) {
            return Api::error('News is not found', $code = 4001);
        }

        return Api::respond('NewsMapper', $news);
    }

}
