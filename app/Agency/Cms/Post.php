<?php  namespace Agency\Cms; 

class Post extends \Eloquent  {

	protected $table = "posts";
	protected $fillable=["title","body","admin_id"];
	

	public function admins()
	{
		return $this->belongsTo("Agency\Cms\Admin");
	}

	public function media()
	{
		return $this->hasMany("Agency\Cms\Media");
	}
	
}