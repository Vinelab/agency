<?php namespace Agency;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */
use Agency\Contracts\ImageInterface;
use Agency\Contracts\MediaInterface;
use NeoEloquent;

class Image extends NeoEloquent implements ImageInterface, MediaInterface
{

    protected $label = 'Image';

    protected $fillable = ['original', 'thumbnail', 'small', 'square', 'description'];

    protected $presets = [
        'original'  => 'original',
        'thumbnail' => 'thumbnail',
        'square'    => 'square',
        'small'     => 'small'
    ];

    /**
     * @return mixed
     */
    public function video()
    {
        return $this->belongsTo('Agency\Video', 'THUMBNAIL');
    }

    /**
     * @return mixed
     */
    public function episodeCover()
    {
        return $this->belongsTo('Agency\Episode', 'COVER_PHOTO');
    }


    /**
     * @return mixed
     */
    public function newsCover()
    {
        return $this->belongsTo('Agency\News', 'COVER_PHOTO');
    }

    /**
     * @return mixed
     */
    public function newsPhotos()
    {
        return $this->belongsToMany('Agency\News', 'PHOTOS');
    }

    /**
     * @return mixed
     */
    public function albumCover()
    {
        return $this->belongsTo('Agency\Album', 'COVER_PHOTO');
    }

    /**
     * @return mixed
     */
    public function albumPhotos()
    {
        return $this->belongsToMany('Agency\Album', 'PHOTOS');
    }

    /**
     * @return mixed
     */
    public function writer()
    {
        return $this->belongsTo('Agency\Writer', 'PICTURE');
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

    /**
     * @param $preset
     *
     * @return string
     */
    public function presetUrl($preset)
    {
        $guid = $this->guid;
        $image = $this->where('guid', '=', $guid)
            ->where('preset', '=', $this->presetType($preset))
            ->first();

        return $image->$preset . '_url';
    }

    /**
     * @param $preset
     *
     * @return mixed
     */
    public function presetType($preset)
    {
        return isset($this->presets[$preset]) ? $this->presets[$preset] : $this->presets['original'];
    }

    /**
     *
     */
    public function postCover()
    {
        $this->belongsTo('Agency\Post', 'COVER_IMAGE');
    }

}
