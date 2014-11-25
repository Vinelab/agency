<?php namespace Agency;

use NeoEloquent;
use Agency\Contracts\ImageInterface;
use Agency\Contracts\MediaInterface;

class Image extends NeoEloquent implements ImageInterface, MediaInterface  {

    protected $label = 'Image';

    protected $fillable = ['original','thumbnail','small', 'square', 'description'];

    protected $presets = [
        'original'  => 'original',
        'thumbnail' => 'thumbnail',
        'square'    => 'square',
        'small'     => 'small'
    ];


  /**
   * get the post that this image belongs to
   *
   * @return Illuminate\Database\Eloquent\Collection of Agency\Post
   */
  public function posts()
    {
        return $this->belongsTo('Agency\Post', 'IMAGE');
    }

    public function students()
    {
        return $this->belongsTo('Starac\Entities\Student', 'IMAGES');
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

    public function presetUrl($preset)
    {
        $guid = $this->guid;
        $image = $this->where('guid', '=', $guid)
      ->where('preset', '=', $this->presetType($preset))
      ->first();

        return $image->$preset.'_url';
    }

  public function presetType($preset)
  {
    return isset($this->presets[$preset]) ? $this->presets[$preset] : $this->presets['original'];
  }


    public function studentNominee()
    {
        return $this->belongsTo('Starac\Entities\Student', 'NOMINEE_IMAGE');
    }

    public function studentProfile()
    {
        return $this->belongsTo('Starac\Entities\Student', 'PROFILE_IMAGE');
    }

    public function iconProfile()
    {
        return $this->belongsTo('Starac\Entities\Student', 'ICON_IMAGE');
    }

    public function postCover()
    {
        $this->belongsTo('Agency\Post', 'COVER_IMAGE');
    }

    public function officialImage()
    {
        return $this->belongsTo('Starac\Entities\Official', 'PROFILE_IMAGE');
    }

}
