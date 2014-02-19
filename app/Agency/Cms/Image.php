<?php  namespace Agency\Cms; 

use Agency\Cms\Contracts\MediaInterface;

class Image extends \Eloquent implements MediaInterface  {

	protected $table = "images";

	protected $fillable = ["url"];

	/**
	 * Get posts
	 * @return collection of "Agency\Cms\Post"
	 */
	public function post()
    {
        return $this->morphMany ("Agency\Cms\Media", "media");
    }

    /**
     * Get Image type 
     * @return string
     */
    public function type()
    {
    	return 'image';
    }


    /**
     * Get Image url
     * @return string
     */
    public function url()
    {
    	$this->url;
    }

}