<?php namespace Agency\Services\Uploader;

use Input;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

abstract class UploaderBase{


    /**
     * creates an object of the data and return it
     *
     * @return \Fahita\Services\Uploader\UploadedPhoto
     */
    public function getData()
    {
        return new UploadedPhoto(Input::get('photo'), Input::get('meta'), Input::get('crop'));
    }

    /**
     * @param $uploadedPhoto
     *
     * @return int
     */
    public function createPhotoFile(&$uploadedPhoto)
    {
        list($meta['type'], $data) = explode(';', $uploadedPhoto->getPhoto());
        list (, $data) = explode(',', $data);
        $data = base64_decode($data);

        $extension = explode('/', $meta['type'])[1];
        $file = md5(microtime(true)) . '.' . $extension;
        $dir_file = storage_path() . '/' . $file;

        $uploadedPhoto->setDirectory($dir_file);

        return file_put_contents($dir_file, $data);
    }

    /**
     * delete the temp photo file
     *
     * @param $uploadedPhoto
     *
     * @return bool
     */
    public function deletePhotoFile($uploadedPhoto)
    {
        // delete the local image after uploading to cdn
        return unlink($uploadedPhoto->getDirectory());
    }

}
