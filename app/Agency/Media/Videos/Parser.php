<?php namespace Agency\Media\Videos;

use Agency\Video;

class Parser implements Contracts\ParserInterface {

	public function make($videos)
	{
		return array_map(function($video){
            if($this->validateYoutubeUrl($video->url))
            {
                return new Video([
                    'url'=>$video->url,
                    'title'=>$video->title,
                    'description'=>$video->desc,
                    'thumbnail'=>$video->src
                ]);
            }
    	}, $videos);
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