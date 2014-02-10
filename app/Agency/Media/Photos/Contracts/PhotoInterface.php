<?php namespace Agency\Media\Photos\Contracts;

use Agency\Media\Photos\Contracts\UploadIntetface as Upload;

interface PhotoInterface {

    public function make(Upload $upload);
}