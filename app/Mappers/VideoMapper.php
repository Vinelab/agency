<?php namespace Agency\Mappers;

use Agency\Api\VideosCollection;

use Agency\Cms\Video;

class VideoMapper{

    protected $video;

    protected $videos_collection;

    public function make($videos)
    {
        $this->videos_collection = new VideosCollection();
        foreach ($videos as $video) {
            $this->videos_collection->push($this->parseAndFill($video));
        }
        return $this->videos_collection;
    }

    public function parseAndFill($video)
    {
        $this->video['url'] = $video->url;
        $this->video['thumbnail'] = $video->thumbnail;
        
        return $this->video;
    }
    
}