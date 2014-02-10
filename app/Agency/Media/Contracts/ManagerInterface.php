<?php namespace Agency\Media\Photos\Contracts;

use Agency\Media\Photos\UploadedPhotosCollection;

interface ManagerInterface {

    public function upload(UploadedPhotosCollection $photo, $directory);
}