<?php  namespace Agency;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Eloquent;

class Post extends Eloquent  {

	protected $table = 'posts';

	protected $fillable=['title','body','admin_id','section_id','publish_date','publish_state','slug'];

	protected $thumbnail;

    public function scopePublished($query)
    {
        return $query->where('publish_state','=','published');
    }

	public function admin()
	{
		return $this->belongsTo('Agency\Cms\Admin');
	}

	public function media()
	{
		return $this->hasMany('Agency\Media');
	}

	public function section()
	{
		return $this->belongsTo('Agency\Section');
	}

	public function tags()
    {
        return $this->belongsToMany('Agency\Tag');
    }

    public function setThumbnail($thumbnail)
    {
    	$this->thumbnail = $thumbnail;
    }

    public function thumbnailURL()
    {
    	if(!is_null($this->thumbnail))
    	{
    		return $this->thumbnail;
    	} else {

            $media = $this->media()->get();

            if(!$media->isempty())
            {
                $media=$this->media()->first()->media;

                if($media->type() == 'image')
                {
                    return $media->presetURL('thumbnail');
                }else{
                    return $media->thumbnail;
                }

                return $this->media()->first()->media->url;
            }

    	}
    }

    public function getAllImages()
    {
        $media = $this->media()->get();
        $images = [];

        if(!$media->isempty())
        {
            foreach ($media as $media_element) {
                if($media_element->media->type() == "image")
                {
                     array_push($images, $media_element->media) ;
                }
            }
        }

        return $images;

    }

    public function getAllVideos()
    {
        $media = $this->media()->get();
        $videos = [];

        if(!$media->isempty())
        {
            foreach ($media as $media_element) {
                if($media_element->media->type()=="video")
                {
                    array_push($videos, $media_element->media);
                }
            }
        }

        return $videos;

    }



}
