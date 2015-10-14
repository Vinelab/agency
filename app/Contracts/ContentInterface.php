<?php namespace Agency\Contracts;

use Vinelab\Minion\Dictionary;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface ContentInterface {

    /**
     * Get the content identifier.
     *
     * @return string
     */
    public function id();

    /**
     * Get the content type.
     *
     * @return string
     */
    public function type();

    /**
     * Get a new Content instance.
     *
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return \Fahita\RealTime\Content
     */
    public static function make(Dictionary $data);
}
