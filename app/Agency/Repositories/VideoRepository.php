<?php  namespace Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Video;
use Agency\Repositories\Contracts\VideoRepositoryInterface;
use Agency\Repositories\Contracts\PostRepositoryInterface;

class VideoRepository extends Repository implements VideoRepositoryInterface {

	public function __construct(Video $video)
	{
		$this->video = $this->model = $video;
	}

	public function create($title, $url, $description, $thumbnail)
	{
		return $this->video->create(compact("url","title","description","thumbnail"));
	}

	public function extractYoutubeId($url)
	{
		if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $matches)) {
			$id = $matches[1];
		} else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $matches)) {
			$id = $matches[1];
		} else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $matches)) {
			$id = $matches[1];
		} else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $matches)) {
			$id = $matches[1];
		} else {
			$id = null;
		}

		return $id;
	}

	

	/**
	 * @override
	 *
	 * @param {array|int|string} $videos_ids
	 * @return boolean
	 */
    public function remove($videos_ids)
    {
    	return $this->video->destroy($videos_ids);
    }

}
