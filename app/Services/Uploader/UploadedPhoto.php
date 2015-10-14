<?php namespace Agency\Services\Uploader;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

class UploadedPhoto{

    protected $photo;
    protected $meta;
    protected $crop;
    protected $directory;

    /**
     * @param $photo
     * @param $meta
     * @param $crop
     */
    public function __construct($photo, $meta, $crop)
    {
        $this->photo = $photo;
        $this->meta = $meta;
        $this->crop = $crop;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return mixed
     */
    public function getCrop()
    {
        return $this->crop;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param mixed $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

}
