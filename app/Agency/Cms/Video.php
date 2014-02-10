<?php  namespace Agency\Cms;

class Video extends \Eloquent  {

	protected $table = "videos";
	protected $fillable = ["url","title","description","thumbnail"];

	public function post()
    {
        return $this -> morphMany ( "Agency\Cms\Media", "media");
    }
	
}