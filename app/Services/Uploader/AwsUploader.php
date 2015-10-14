<?php namespace Agency\Services\Uploader;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Contracts\Repositories\ImageRepositoryInterface;
use Agency\Contracts\UploaderDriverInterface;
use Agency\Media\Photos\Contracts\ManagerInterface as PhotosManagerInterface;
use Agency\Media\Photos\UploadedPhotosCollection;
use Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use \Agency\Media\Photos\UploadedPhoto;

class AwsUploader extends UploaderBase implements UploaderDriverInterface
{

    /**
     * @var \Agency\Media\Photos\Contracts\ManagerInterface
     */
    protected $photo_manager;

    /**
     * @var \Agency\Contracts\Repositories\ImageRepositoryInterface
     */
    protected $photos;

    /**
     * @param \Agency\Media\Photos\Contracts\ManagerInterface         $photo_manager
     * @param \Agency\Contracts\Repositories\ImageRepositoryInterface $photos
     */
    public function __construct(
        PhotosManagerInterface $photo_manager,
        ImageRepositoryInterface $photos
    ) {
        $this->photo_manager = $photo_manager;
        $this->photos = $photos;
    }


    /**
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $uploadedPhoto = $this->getData();

        $uploaded = null;

        if ($this->createPhotoFile($uploadedPhoto)) {

            $uploaded = $this->aws(
                $uploadedPhoto->getDirectory(),
                $uploadedPhoto->getMeta(),
                $uploadedPhoto->getCrop()
            );

        }

        if ($uploaded) { $this->deletePhotoFile($uploadedPhoto); }

        return $this->prepareResponse($uploaded);
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
        // prepare the crop data for the UploadedPhoto class
        $crop_data = [
            'width'       => $meta['width'],
            'height'      => $meta['height'],
            'crop_width'  => $crop['width'],
            'crop_height' => $crop['height'],
            'crop_x'      => $crop['x'],
            'crop_y'      => $crop['y'],
        ];

        // instantiate a photos collection
        $photos = new UploadedPhotosCollection;

        $photo = UploadedPhoto::make($uploadedFile, $crop_data);

        $photos->push($photo);

        // upload to 'photos' directory
        return $this->photo_manager->upload($photos, 'photos');
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
        if (is_null($uploadedObj)) { return null; }

        $uploaded = $uploadedObj->toArray();

        foreach ($uploaded as $up) {
            $uploaded = $up;
        }

        return [
            'original'  => $uploaded['original']->url,
            'small'     => $uploaded['small']->url,
            'thumbnail' => $uploaded['thumbnail']->url,
            'square'    => $uploaded['square']->url,
        ];

    }

}
