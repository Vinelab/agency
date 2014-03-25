<?php namespace Agency\Contracts;

interface ImageInterface {

    /**
     * return the real preset name as
     * stored in the database, returns the original
     * by default
     *
     * @param {string} $preset
     *
     * @return string
     */
    public function presetType($preset);
}
