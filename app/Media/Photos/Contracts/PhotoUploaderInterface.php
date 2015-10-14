<?php namespace Agency\Media\Photos\Contracts;

use Symfony\Component\HttpFoundation\File\UploadedFile as File;
use Intervention\Image\Image;

interface PhotoUploaderInterface {

    public function upload(Image $image, $directory, $name, $mime);
}
