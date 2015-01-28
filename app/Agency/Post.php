<?php  namespace Agency;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use NeoEloquent, Config;

use Carbon\Carbon;

use DB, Cache;

use Vinelab\NeoEloquent\Eloquent\SoftDeletingTrait;


class Post extends NeoEloquent  {

    use SoftDeletingTrait;

    protected $label = ['Post'];

    protected $fillable=['title','body','admin_id','section_id', 'featured','publish_date','publish_state','slug'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $thumbnail;

    public $timestamps = true;


    public function scopePublished($query)
    {

        $passed_scheduled_posts = $this->where('publish_state','=', 'scheduled')->where('publish_date','<=',Carbon::now('Asia/Beirut')->toDateTimeString())->get();

        foreach ($passed_scheduled_posts as $post) {
            $post->update([
                'publish_state' => 'published'
            ]);
        }

        return $query->where('publish_state','published');
    }


    public function nearestScheduledPost()
    {
        $waiting_scheduled_posts = $this->where('publish_state','=', 'scheduled')->where('publish_date','>',Carbon::now('Asia/Beirut')->toDateTimeString())->get();

         if($waiting_scheduled_posts->count() > 0)
        {
            return $waiting_scheduled_posts->sortBy('publish_date')->first();
        }

        return null;
    }

    public function admin()
    {
        return $this->belongsTo('Agency\Cms\Admin', 'ADMIN');
    }

    public function images()
    {
        return $this->hasMany('Agency\Image', 'IMAGE');
    }

    public function coverImage()
    {
        return $this->hasOne('Agency\Image', 'COVER_IMAGE');
    }

    public function videos()
    {
        return $this->hasMany('Agency\Video', 'VIDEO');

    }

    public function section()
    {
        return $this->belongsTo("Agency\Cms\Section", "POST");
    }

    public function tags()
    {
        return $this->belongsToMany("Agency\Tag", "TAG");
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function thumbnailURL()
    {
        $cover_image = $this->coverImage();
        if(($cover_image->first()))
        {
            return $cover_image->first()->thumbnail;
        } else {
          $video = $this->videos()->first();
            if(isset($video->thumbnail))
            {
                return $video->thumbnail;
            }
        }

    }


    public function shareUrl()
    {
        return Config::get('share.url').'/posts/'.$this->slug;
    }




}
