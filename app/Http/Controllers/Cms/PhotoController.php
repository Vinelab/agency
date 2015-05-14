<?php namespace Agency\Http\Controllers\Cms;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Image;
use App;
use Auth;
use Clockwork;
use Config;
use Agency\Contracts\PhotosServiceInterface;
use Agency\Contracts\Repositories\ImageRepositoryInterface as PhotoRepositoryInterface;
use Agency\Services\Uploader\Uploader;
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
    protected $photos;

    /**
     * @var \Agency\Contracts\PhotosServiceInterface
     */
    protected $photos_service;

    /**
     * @var \Agency\Services\Uploader\Uploader
     */
    protected $uploader;

    /**
     * @param \Agency\Contracts\PhotosServiceInterface                $photos_service
     * @param \Agency\Contracts\Repositories\ImageRepositoryInterface $photos
     * @param \Agency\Contracts\NewsRepositoryInterface               $news
     * @param \Agency\Services\Uploader\Uploader                      $uploader
     */
    public function __construct(
        PhotosServiceInterface $photos_service,
        PhotoRepositoryInterface $photos,
        Uploader $uploader
    ) {
        $this->photos_service = $photos_service;
        $this->photos = $photos;
        $this->uploader = $uploader;
    }

    /**
     * return the paginated images as JSON response
     *
     * @return mixed
     */
    public function index()
    {
        $photos = $this->photos->getAll(8, 'created_at', 'DESC');

        return Response::json([
            'status' => 'success',
            'photos' => $photos->toJson(),
        ]);
    }

    /**
     * @return mixed
     */
    public function upload()
    {
        $response = ['status' => 'Failed!'];

        $result = $this->uploader->upload();

        if ($result) {
            $response = [
                'status' => 'Success',
                'photos' => $result
            ];
        }

        return Response::json($response);
    }

    /**
     * upload photos for the embedded uploader of the laravel-editor package
     *
     * @return mixed
     */
    public function embedUpload()
    {
        $response = [];

        $result = $this->uploader->upload();

        if ($result) {
            $response = [ 'url' => $result['original'] ];
        }

        return Response::json($response);
    }

}
