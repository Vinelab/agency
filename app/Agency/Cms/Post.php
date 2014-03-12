<?php  namespace Agency\Cms; 

class Post extends \Eloquent  {

	protected $table = "posts";
	protected $fillable=["title","body","admin_id","section_id","publish_date","publish_state","slug"];
	
	protected $thumbnail;	

	public function admin()
	{
		return $this->belongsTo("Agency\Cms\Admin");
	}

	public function media()
	{
		return $this->hasMany("Agency\Cms\Media");
	}

	public function section()
	{
		return $this->belongsTo("Agency\Cms\Section");
	}

	public function tags()
    {
        return $this->belongsToMany("Agency\Cms\Tag");
    }

    public function setThumbnail($thumbnail)
    {
    	$this->thumbnail = $thumbnail;
    }

    public function thumbnail()
    {
    	if(!is_null($this->thumbnail))
    	{
    		return $this->thumbnail;
    	} else {
    		$media=$this->media()->first()->media;
    		if($media->type()=="image")
    		{
    			return $media->thumbnail();
    		}else{
    			return $media->thumbnail;
    		}
    		return $this->media()->first()->media->url;
    	}
    }

}