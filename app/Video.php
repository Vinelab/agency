<?php namespace Agency;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */
use Agency\Contracts\MediaInterface;
use Agency\Contracts\VideoInterface;
use NeoEloquent;
use Vinelab\Youtube\date;
use Vinelab\Youtube\ResourceInterface;

class Video extends NeoEloquent implements MediaInterface, VideoInterface, ResourceInterface
{

    protected $lable = 'Video';

    protected $fillable = ['youtube_id', 'url', 'title', 'description', 'sync_enabled', 'synced_at', 'etag', 'end'];

    /**
     * @return mixed
     */
    public function posts()
    {
        return $this->belongsTo('Agency\Post', 'VIDEO');
    }

    /**
     * @return mixed
     */
    public function live()
    {
        return $this->belongsTo('Agency\Live', 'VIDEO');
    }

    /**
     * @return mixed
     */
    public function behindTheScenes()
    {
        return $this->belongsTo('Agency\BehindTheScenes', 'VIDEO');
    }

    /**
     * @return mixed
     */
    public function thumbnail()
    {
        return $this->hasOne('Agency\Image', 'THUMBNAIL');
    }

    /**
     * @return mixed
     */
    public function type()
    {
        return 'video';
    }

    /**
     * @return mixed
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Return raw youtube information
     * @return array
     */
    public function getYoutubeInfo()
    {
        return [
            'id'           => $this->getKey(),
            'youtube_id'   => isset($this->youtube_id) ? $this->youtube_id : null,
            'etag'         => isset($this->etag) ? $this->etag : null,
            'sync_enabled' => isset($this->sync_enabled) ? $this->sync_enabled : null,
            'synced_at'    => isset($this->synced_at) ? $this->synced_at : null,
            'url'          => isset($this->url) ? $this->url : null
        ];
    }

    /**
     * Return the youtube synced_at value
     * @return date
     */
    public function youtubeSyncedAt()
    {
        return ['synced_at' => isset($this->synced_at) ? $this->synced_at : null];
    }
}
