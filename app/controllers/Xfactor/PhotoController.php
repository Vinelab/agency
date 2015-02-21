<?php namespace Agency\Cms\Controllers;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Image;
use App;
use Auth;
use Clockwork;
use Config;
use Xfactor\Contracts\PhotosServiceInterface;
use Agency\Contracts\Repositories\ImageRepositoryInterface as PhotoRepositoryInterface;
use Input;
use Redirect;
use Response;
use Session;
use View;

class PhotoController extends Controller
{

    /**
     * @var \Agency\Contracts\Repositories\ImageRepositoryInterface
     */
    protected $photo_repo;

    /**
     * @var \Xfactor\Contracts\PhotosServiceInterface
     */
    protected $photos_service;

    /**
     * @param \Xfactor\Contracts\PhotosServiceInterface                $photos_service
     * @param \Agency\Contracts\Repositories\ImageRepositoryInterface $photo_repo
     * @param \Xfactor\Contracts\NewsRepositoryInterface               $news_repo
     */
    public function __construct(
        PhotosServiceInterface $photos_service,
        PhotoRepositoryInterface $photo_repo) 
    {
        $this->photos_service = $photos_service;
        $this->news_repo = $news_repo;
        $this->photo_repo = $photo_repo;

    }

    /**
     * @return mixed
     */
    public function store()
    {
        return $this->photos_service->upload();
    }

}
