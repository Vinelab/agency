<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\VideosRepositoryInterface;
use DB;
use Agency\Cms\Video;

class VideoRepository extends Repository implements VideosRepositoryInterface {

	public function __construct(Video $video)
	{
		$this->video = $this->model =$video;
	}
	
	public function create($url,$title,$description,$thumbnail)
	{
		$this->video=$this->video->create(compact("url","title","description","thumbnail"));
		$this->video->save();
		return $this->video;
	}

	public function get_youtube_id($url)
	{
		if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
		  $values = $id[1];
		} else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
		  $values = $id[1];
		} else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
		  $values = $id[1];
		} else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
		  $values = $id[1];
		} else {   
		// not an youtube video
		}

		return $values;
	}

	public function validate_url($url)
	{
		$rx = '~
    	^(?:https?://)?              # Optional protocol
     	(?:www\.)?                  # Optional subdomain
     	(?:youtube\.com|youtu\.be)  # Mandatory domain name
     	/watch\?v=([^&]+)           # URI with video id as capture group 1
     	~x';

		$has_match = preg_match($rx, $url, $matches);
		return $has_match;
	}


}