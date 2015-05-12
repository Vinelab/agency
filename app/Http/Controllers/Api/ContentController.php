<?php namespace Agency\Http\Controllers\Api;

use Illuminate\Http\Request;
use Agency\Contracts\ContentRepositoryInterface;
use Agency\Contracts\ContentServiceInterface;
use Agency\Http\Controllers\Controller;
use Which;
use Api;

/**
 * Class ContentController
 *
 * @category Controller
 * @package  Agency\Http\Controllers\Api
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ContentController extends Controller
{

    /**
     * content repository instance
     */
    protected $content;

    /**
     * @param \Agency\Contracts\ContentRepositoryInterface $content
     * @param \Agency\Contracts\ContentServiceInterface    $content_service
     */
    public function __construct(ContentRepositoryInterface $content, ContentServiceInterface $content_service)
    {
        $this->content = $content;
        $this->content_service = $content_service;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $featured_input = $request->get('featured');

        if ($this->filterAgainstFeatured($featured_input)) {

            $content = $this->content_service->allPublishedAndFeatured(
                Api::limit(),
                [],
                $this->isFeatured($featured_input)
            );

        } else {
            $content = $this->content_service->allPublished(Api::limit());
        }

        return Api::respond('ContentMapper', $content);
    }

    /**
     * check if the featured parameter exist
     *
     * @param $featured_input
     *
     * @return bool
     */
    private function filterAgainstFeatured($featured_input)
    {
        return !is_null($featured_input) ? $this->validFeature($featured_input) : false;
    }

    /**
     * validate feature parameter is 0 or 1
     *
     * @param $featured_input
     *
     * @return bool
     */
    public function validFeature($featured_input)
    {
        $valid = false;

        if (!is_numeric($featured_input)) {
            $valid = false;
        }

        if ($featured_input == '0' || $featured_input == '1') {
            $valid = true;
        }

        return $valid;
    }

    /**
     * get the featured value is true or false (based on the 1 and 0)
     *
     * @param $featured_input
     *
     * @return bool
     */
    private function isFeatured($featured_input)
    {
        $featured = false;

        if ($featured_input == '1') {
            $featured = true;
        }

        return $featured;
    }

}
