<?php  namespace Agency\Repositories\Contracts;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface VideoRepositoryInterface {

	/**
	 * create a new Video
	 *
	 * @param {string} $url
	 * @param {string} $title
	 * @param {string} $description
	 * @param {string} $thumbnail
	 *
	 * @return Agency\Video
	 */
	public function create($url,$title,$description,$thumbnail);

	/**
	 * extract the youtube video id from
	 * the URL
	 *
	 * @param {string} $url
	 *
	 * @return string
	 */
	public function extractYoutubeId($url);

	/**
	 * validate the passed URL for being
	 * a youtube URL or not
	 *
	 * @param {string} $url
	 * @return boolean
	 */
	public function validateYoutubeUrl($url);

}
