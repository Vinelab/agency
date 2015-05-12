<?php  namespace Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Video;
use Agency\Repositories\Contracts\VideoRepositoryInterface;
use Agency\Repositories\Contracts\PostRepositoryInterface;

class VideoRepository extends Repository implements VideoRepositoryInterface {

	public function __construct(Video $video)
	{
		$this->video = $this->model = $video;
	}

	public function create($title, $url, $description)
	{
		return $this->video->create(compact("url","title","description"));
	}

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
    public function createWith($youtube_id, $url, $end, $title, $description, $sync_enabled, $synced_at, $etag, $relations)
    {
        return $this->video->createWith(
            [
                'youtube_id' => $youtube_id,
                'title' => $title,
                'url' => $url,
                'end' => $end,
                'description' => $description,
                'sync_enabled' => $sync_enabled,
                'synced_at' => $synced_at,
                'etag' => $etag,
            ],
            $relations
        );
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

    public function detachFromLive($id)
    {
        return $this->video->find($id)->live()->edge()->delete();
    }

    public function detachFromBehindTheScenes($id)
    {
        return $this->video->find($id)->behindTheScenes()->edge()->delete();
    }
}
