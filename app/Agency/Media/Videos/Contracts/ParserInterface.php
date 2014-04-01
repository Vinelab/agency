<?php namespace Agency\Media\Videos\Contracts;

interface ParserInterface {

	public function make($videos);

	/**
	 * validate the passed URL for being
	 * a youtube URL or not
	 *
	 * @param {string} $url
	 * @return boolean
	 */
	public function validateYoutubeUrl($url);

}