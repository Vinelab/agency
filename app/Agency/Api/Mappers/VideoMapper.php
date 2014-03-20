<?php namespace Agency\Api\Mappers;

use Agency\Api\VideosCollection;

use Agency\Cms\Video;

class VideoMapper{

	protected $video;

	protected $videosCollection;

	public function make($videos)
	{
		$this->videosCollection = new VideosCollection();
		foreach ($videos as $video) {
			$this->videosCollection->push($this->parseAndFill($video));
		}
		return $this->videosCollection;
	}

	public function parseAndFill($video)
	{
		$this->video['url'] = $video->url;
		$this->video['thumbnail'] = $video->thumbnail;
		
		return $this->video;
	}
	
}