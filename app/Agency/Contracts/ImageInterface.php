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

    /**
     * return the image URL according to a preset
     *
     * @param {string} $preset must be one of Agency\Image::presets
     * @return string
     */
    public function presetUrl($preset);
}
