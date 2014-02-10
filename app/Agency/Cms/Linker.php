<?php  namespace Agency\Cms; 

class Image extends \Eloquent  {

	protected $table = "images";
	protected $fillable = ["url"];

	public function post()
    {
        return $this -> morphMany ( "Agency\Cms\Media", "media" );
    }

}