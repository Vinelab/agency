<?php  namespace Agency;

use Eloquent;
use Agency\Contracts\MediaInterface;
use Agency\Contracts\VideoInterface;

class Video extends Eloquent implements MediaInterface, VideoInterface  {

	protected $table = 'videos';

	protected $fillable = ['url', 'title', 'description', 'thumbnail'];

    public function media()
    {
        return $this->morphToMany('Agency\Post', 'media');
    }

    public function type()
    {
    	return 'video';
    }

    public function url()
    {
    	return $this->url;
    }

}
