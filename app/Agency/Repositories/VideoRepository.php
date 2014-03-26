<?php  namespace Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Video;
use Agency\Repositories\Contracts\VideoRepositoryInterface;

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

	public function validateYoutubeUrl($url)
	{
		$pattern = '~
	    	^(?:https?://)?              # Optional protocol
	     	(?:www\.)?                  # Optional subdomain
	     	(?:youtube\.com|youtu\.be)  # Mandatory domain name
	     	(/embed/([^&]+))?           # URI with video id as capture group 1
	     	~x';

		return (boolean) preg_match($pattern, $url, $matches);
	}

}
