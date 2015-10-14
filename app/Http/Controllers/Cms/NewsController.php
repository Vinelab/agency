<?php namespace Agency\Http\Controllers\Cms;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Contracts\Cms\Repositories\SectionRepositoryInterface;
use Agency\Contracts\ContentServiceInterface;
use Agency\Contracts\HelperInterface;
use Agency\Contracts\NewsServiceInterface;
use Agency\Contracts\NewsValidatorInterface;
use Agency\Contracts\PhotosServiceInterface;
use Agency\Contracts\WriterRepositoryInterface;
use Agency\Exceptions\InvalidNewsException;
use Agency\Facades\Cms\Which;
use Agency\Facades\Helper;
use Auth;
use Clockwork;
use Input;
use Redirect;
use Response;
use View;

class NewsController extends Controller
{

    /**
     * the news alias in the database
     */
    const NEWS_ALIAS = 'news';

    /**
     * @var \Agency\Contracts\Cms\Repositories\SectionRepositoryInterface
     */
    protected $sections;

    /**
     * @var \Fahita\Contracts\NewsServiceInterface
     */
    protected $news_service;

    /**
     * @var \Agency\Cms\Section
     */
    protected $section;

    /**
     * @var \Fahita\Contracts\WriterRepositoryInterface
     */
    protected $writers;

    /**
     * @var \Fahita\Contracts\NewsValidatorInterface
     */
    protected $news_validator;

    /**
     * @var \Fahita\Contracts\PhotosServiceInterface
     */
    protected $photos_service;

    /**
     * @var \Agency\Http\Controllers\Cms\HelperInterface
     */
    protected $helper;

    /**
     * @var \Agency\Contracts\ContentServiceInterface
     */
    private $content_service;

    /**
     * @param \Agency\Contracts\Cms\Repositories\SectionRepositoryInterface $sections
     * @param \Agency\Contracts\NewsServiceInterface                        $news_service
     * @param \Agency\Contracts\WriterRepositoryInterface                   $writers
     * @param \Agency\Contracts\NewsValidatorInterface                      $news_validator
     * @param \Agency\Contracts\PhotosServiceInterface                      $photos_service
     * @param \Agency\Contracts\ContentServiceInterface                     $content_service
     * @param \Agency\Contracts\HelperInterface                             $helper
     */
    public function __construct(
        SectionRepositoryInterface $sections,
        NewsServiceInterface $news_service,
        WriterRepositoryInterface $writers,
        NewsValidatorInterface $news_validator,
        PhotosServiceInterface $photos_service,
        ContentServiceInterface $content_service,
        HelperInterface $helper
    ) {
        parent::__construct();

        $this->sections = $sections;
        $this->news_service = $news_service;
        $this->writers = $writers;
        $this->news_validator = $news_validator;
        $this->photos_service = $photos_service;
        $this->content_service = $content_service;
        $this->helper = $helper;

        $this->section = $this->sections->findBy("alias", self::NEWS_ALIAS);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        if (!Auth::hasPermission("read")) {
            return Redirect::back();
        }

        // get news of this section
        $news = $this->news_service->all(Which::category());

        return View::make("cms.pages.news.index")
            ->with('section', $this->section)
            ->with('news', $news);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::hasPermission("create")) {
            return Redirect::back();
        }

        // get timezones
        $timezones = Helper::getTimezones();


        // get all writers
        $writers = $this->writers->all();


        return View::make("cms.pages.news.create")
            ->with('section', $this->section)
            ->with('timezones', $timezones)
            ->with('errors', (Input::get('errors')) ? Input::get('errors') : null)
            ->with('writers', $writers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!Auth::hasPermission("create")) {
            return Redirect::back();
        }

        // validate the input
        try {
            $this->news_validator->validateCreate(Input::all());
        } catch(InvalidNewsException $e) {
            return Redirect::route("cms.news.create", [
                'category' => Which::category()->alias,
                'errors'   => $e->messages()
            ])->withInput();
        }

        $this->news_service->create();

        return Redirect::route("cms.news", [
            'category' => Which::category()->alias
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $alias
     *
     * @return Response
     */
    public function show($idOrSlug)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  $idOrSlug
     *
     * @return Response
     */
    public function edit($idOrSlug)
    {
        if (!Auth::hasPermission("update")) {
            return Redirect::back();
        }

        // get the news article
        $news = $this->news_service->findByIdOrSlug($idOrSlug);

        // get all writers
        $writers = $this->writers->all();

        // get timezones
        $timezones = Helper::getTimezones();

        return View::make("cms.pages.news.edit")
            ->with('section', $this->section)
            ->with('writers', $writers)
            ->with('timezones', $timezones)
            ->with('updating', true)// tell the form it's update
            ->with('errors', (Input::get('errors')) ? Input::get('errors') : null)
            ->with('edit_news', $news)
            ->with('limited_editing', $this->content_service->isLimitedEditing($news));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $idOrSlug
     *
     * @return mixed
     */
    public function update($idOrSlug)
    {
        if (!Auth::hasPermission("update")) {
            return Redirect::back();
        }

        // validate the input
        try {
            $this->news_validator->validateUpdate(Input::all());
        } catch(InvalidNewsException $e) {
            return Redirect::route("cms.news.edit", [
                'news_slug' => $idOrSlug,
                'category'  => Which::category()->alias,
                'errors'    => $e->messages()
            ])->withInput();
        }

        $this->news_service->update($idOrSlug);

        return Redirect::route("cms.news", [
            'category' => Which::category()->alias
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $idOrSlug
     *
     * @return Response
     */
    public function destroy($idOrSlug)
    {
        if (!Auth::hasPermission("delete")) {
            return Redirect::back();
        }

        $done = $this->news_service->delete($idOrSlug);

        return Response::json([
            'status' => ($done) ? 'success' : 'failed'
        ]);
    }

    /**
     * detach photo form the model
     *
     * @return mixed
     */
    public function detach()
    {
        $detached = $this->news_service->detachPhoto(Input::get('news_slug'), Input::get('photo_id'));

        return Response::json([
            'status' => ($detached) ? 'success' : 'failed',
            'error'  => (!$detached) ? 'Error: could not delete the image' : ''
        ]);
    }


}
