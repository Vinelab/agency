<?php namespace Xfactor\Services;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Contracts\Repositories\ImageRepositoryInterface;
use Agency\Media\Photos\Contracts\ManagerInterface as PhotosManagerInterface;
use Agency\Media\Photos\UploadedPhoto;
use Agency\Media\Photos\UploadedPhotosCollection;
use Xfactor\Contracts\Services\PhotosServiceInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotosService implements PhotosServiceInterface{

    /**
     * @var \Agency\Media\Photos\Contracts\ManagerInterface
     */
    protected $photo_manager;

    /**
     * @var \Agency\Contracts\Repositories\ImageRepositoryInterface
     */
    protected $photo_repo;

    /**
     * @param \Agency\Media\Photos\Contracts\ManagerInterface         $photo_manager
     * @param \Agency\Contracts\Repositories\ImageRepositoryInterface $photo_repo
     */
    public function __construct(
        PhotosManagerInterface $photo_manager,
        ImageRepositoryInterface $photo_repo
    ) {
        $this->photo_manager = $photo_manager;
        $this->photo_repo = $photo_repo;
    }

    /**
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $data = Input::get('photo');
        $meta = Input::get('meta');
        $crop = Input::get('crop');

        list($meta['type'], $data) = explode(';', $data);
        list (, $data) = explode(',', $data);
        $data = base64_decode($data);

        $extension = explode('/', $meta['type'])[1];
        $file = md5(microtime(true)) . '.' . $extension;
        $dir_file = public_path() . '/' . $file;

        $success = file_put_contents($dir_file, $data);

        if ($success) {
            $uploaded = $this->aws($dir_file, $meta, $crop);

            // delete the local image after uploading to cdn
            unlink($dir_file);

            $result = $this->prepareResponse($uploaded);

            if($uploaded){
                return Response::json([
                    'photos' => $result
                ]);
            }
        }

        http_response_code(400);
        echo 'FAILED to upload';
        return false;
    }


    /**
     * upload to AWS
     *
     * @param $file
     * @param $meta
     * @param $crop
     *
     * @return bool
     */
    private function aws($file, $meta, $crop)
    {
        $uploadedFile = new UploadedFile($file, $file);

        // instantiate a photos collection
        $photos = new UploadedPhotosCollection;

        // prepare the crop data for the UploadedPhoto class
        $crop_data = [
            'width' => $meta['width'],
            'height' => $meta['height'],
            'crop_width' => $crop['width'],
            'crop_height' => $crop['height'],
            'crop_x' => $crop['x'],
            'crop_y' => $crop['y'],
        ];

        $photo = UploadedPhoto::make($uploadedFile, $crop_data);
        $photos->push($photo);

        // upload to 'artists/webs' directory
        return $this->photo_manager->upload($photos, 'artists/webs');
    }


    /**
     * convert the uploaded object to an array
     * to be returned as a json response on photo upload
     *
     * @param $uploadedObj
     *
     * @return array
     */
    private function prepareResponse($uploadedObj)
    {
        $uploaded = $uploadedObj->toArray();

        // TODO: refactor this shit
        foreach($uploaded as $up){
            $uploaded = $up;
        }

        return [
            'original' => $uploaded['original']->url,
            'small' => $uploaded['small']->url,
            'thumbnail' => $uploaded['thumbnail']->url,
            'square' => $uploaded['square']->url,
        ];

    }

    /**
     * call the photo repository to create a photo object
     *
     * @param $data
     *
     * @return mixed
     */
    public function parse($data)
    {
        return $this->photo_repo->createWithUri(
            $data['original'],
            $data['thumbnail'],
            $data['small'],
            $data['square']
        );
    }

}
