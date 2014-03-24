<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\VideoRepositoryInterface;
use DB;
use Agency\Cms\Video;

class VideoRepository extends Repository implements VideoRepositoryInterface {

	public function __construct(Video $video)
	{
		$this->video = $this->model =$video;
	}
	
	public function create($url,$title,$description,$thumbnail)
	{
		$this->video=$this->video->create(compact("url","title","description","thumbnail"));
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
     	/embed/([^&]+)           # URI with video id as capture group 1
     	~x';

		$has_match = preg_match($rx, $url, $matches);
		return $has_match;
	}

	public function detachAll($post)
	{
		$old_videos=$post->media()->where('media_type','=','Agency\Cms\Video')->get();
		foreach ($old_videos as $key => $video) {
			$media_id=$video->media_id;
			$this->video->find($media_id)->delete();
			$video->delete();
		}
	}


}