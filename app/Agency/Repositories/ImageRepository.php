<?php namespace  Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Contracts\Repositories\ImageRepositoryInterface;


use DB, File;
use Agency\Media\Photos\Photo;
use Agency\Contracts\ImageInterface;
use Agency\Image;

use Agency\Contracts\HelperInterface;


class ImageRepository extends Repository implements ImageRepositoryInterface {

	public function __construct(ImageInterface $image,
								HelperInterface $helper)
	{
		$this->image = $this->model = $image;
		$this->helper = $helper;
	}

	public function create( Photo $original,
                            Photo $thumbnail,
                            Photo $small,
                            Photo $square,
                            $description = null)
	{

		$original_image = $this->image->create([
			'original'	=> 	$original->url,
			'thumbnail'	=>	$thumbnail->url,
			'small'		=>	$small->url,
			'square'	=>	$square->url,
			'description' => $description
		]);


		return $original_image;
	}


  public function update($id,
                          Photo $original,
                          Photo $thumbnail,
                          Photo $small,
                          Photo $square)
  {
    $image = $this->find($id);
    $image->original = $original->url;
    $image->thumbnail = $thumbnail->url;
    $image->small = $small->url;
    $image->square = $square->url;

    $image->save();
    return $image;
  }

	public function getThumbnail($guid)
	{
		return $this->image
            ->where('guid', '=', $guid)
            ->where('preset', '=', $this->image->presetType('thumbnail'))
            ->first();
	}

	 public function getByGuid($guid)
    {
        return $this->image->where('guid','=',$guid)->get();
    }

    /**
	 * @override
	 *
	 * @param {array|int|string} $image_ids
	 * @return boolean
	 */
    public function remove($image_ids)
    {
    	return $this->image->destroy($image_ids);
    }

    public function store($images_without_original)
    {
    	return $this->image->insert($images_without_original);
    }

    /**
     * return preset type
     * @param  string $type
     * @return string
     */
   	public function presetType($type)
   	{
   		return $this->image->presetType($type);
   	}

   	/**
   	 * return Image instance
   	 * @param  string $url
   	 * @param  string $preset
   	 * @param  string $guid
   	 * @return Agency\Image
   	 */
   	public function newImage($url, $preset, $guid)
   	{
   		return new Image(['url' => $url, 'preset' => $preset, 'guid' => $guid]);
   	}



}
