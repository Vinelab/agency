<?php namespace Agency\Media\Photos\Contracts;

interface UploadedPhotoInterface {

    /**
     * @return Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function file();
    /**
     * @return string
     */
    public function name();
    /**
     * @return string
     */
    public function path();
    /**
     * @return string
     */
    public function extension();
    /**
     * @return string
     */
    public function mime();
    /**
     * @return array
     */
    public function meta();
    /**
     * @return int
     */
    public function width();
    /**
     * @return int
     */
    public function height();
    /**
     * @return int
     */
    public function cropWidth();
    /**
     * @return int
     */
    public function cropHeight();
    /**
     * @return int
     */
    public function cropX();
    /**
     * @return int
     */
    public function cropY();
}
