<?php namespace Agency\Media\Photos;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Media\Photos\Contracts\UploadInterface;

class Photo {

    public function make(UploadInterface $upload)
    {
        $photo = new static;

        $photo->fill($upload);

        return $photo;
    }

    public function fill(UploadInterface $upload)
    {
        $this->id           = $upload->getId();
        $this->url          = $upload->getURL();
        $this->uploaded_at  = $upload->time();
    }
}
