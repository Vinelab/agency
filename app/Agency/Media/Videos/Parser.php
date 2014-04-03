<?php namespace Agency\Media\Videos;
use Agency\Validators\Contracts\VideoValidatorInterface;
use Agency\Video;

class Parser implements Contracts\ParserInterface {

    public function __construct(VideoValidatorInterface $video_validator)
    {
        $this->video_validator = $video_validator;
    }

	public function make($videos)
	{
        $video_validator = $this->video_validator;
		return array_map(function($video) use($video_validator){
            if($video_validator->validate(["url"=>$video->url]))
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

}