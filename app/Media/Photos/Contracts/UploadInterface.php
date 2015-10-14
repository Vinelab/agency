<?php namespace Agency\Media\Photos\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface UploadInterface {

    /**
     * Returns the upload response data.
     *
     * @return mixed
     */
    public function data();

    /**
     * Returns the time when upload was done.
     *
     * @return DateTime
     */
    public function time();

    /**
     * Returns the request/object identifier.
     *
     * @return string
     */
    public function getId();

    /**
     * Returns the reference URL to the object.
     *
     * @return string
     */
    public function getURL();

    /**
     * Returns the last updated date of the object.
     *
     * @return string
     */
    public function getETag();
}
