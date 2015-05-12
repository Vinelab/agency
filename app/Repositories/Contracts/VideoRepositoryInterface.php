<?php  namespace Agency\Repositories\Contracts;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface VideoRepositoryInterface {

    /**
     * @param $url
     * @param $title
     * @param $description
     *
     * @return mixed
     */
	public function create($url,$title,$description);

    /**
     * @param $youtube_id
     * @param $url
     * @param $title
     * @param $description
     * @param $sync_enabled
     * @param $synced_at
     * @param $etag
     * @param $relations
     *
     * @return mixed
     */
    public function createWith($youtube_id, $url, $end, $title, $description, $sync_enabled, $synced_at, $etag, $relations);

	/**
	 * extract the youtube video id from
	 * the URL
	 *
	 * @param {string} $url
	 *
	 * @return string
	 */
	public function extractYoutubeId($url);



}
