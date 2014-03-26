<?php  namespace Agency;

use Eloquent;
use Agency\Contracts\MediaInterface;

class Video extends Eloquent implements MediaInterface  {

	protected $table = 'videos';

	protected $fillable = ['url', 'title', 'description', 'thumbnail'];

	public function post()
    {
        return $this->morphMany('Agency\Cms\Media', 'media');
    }

    public function type()
    {
    	return 'video';
    }

    public function url()
    {
    	return $this->url;
    }

    public function presetURL($preset)
    {

    }

}
