<?php namespace Agency\Services;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Contracts\Repositories\ImageRepositoryInterface;
use Agency\Media\Photos\Contracts\ManagerInterface as PhotosManagerInterface;
use Agency\Contracts\PhotosServiceInterface;

class PhotosService extends AgencyService implements PhotosServiceInterface{

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
     * call the photo repository to create a photo object
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        return $this->photos->createWithUri(
            $data['original'],
            $data['thumbnail'],
            $data['small'],
            $data['square']
        );
    }

}
