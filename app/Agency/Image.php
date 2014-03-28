<?php  namespace Agency;

/**
* @author Ibrahim Fleifel <ibrahim@vinelab.com>
* @author Abed Halawi <abed.halawi@vinelab.com>
*/

use Eloquent;
use Agency\Contracts\ImageInterface;
use Agency\Contracts\MediaInterface;

class Image extends Eloquent implements ImageInterface, MediaInterface  {

	protected $table = 'images';

	protected $fillable = ['url','preset','guid'];

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
        return $this->morphToMany('Agency\Post');
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

        return $image->url;
    }

	public function presetType($preset)
	{
		return isset($this->presets[$preset]) ? $this->presets[$preset] : $this->presets['original'];
	}

   

}
