<?php  namespace Agency;

use NeoEloquent;
use Agency\Contracts\MediaInterface;
use Agency\Contracts\VideoInterface;

class Video extends NeoEloquent implements MediaInterface, VideoInterface  {

    protected $lable = 'Video';

    protected $fillable = ['youtube_id','url', 'title', 'description', 'thumbnail'];

   public function posts()
   {
        return $this->belongsTo('Agency\Post', 'VIDEO');
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
