<?php namespace  Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Repositories\Contracts\ImageRepositoryInterface;


use DB, File;
use Agency\Media\Photos\Photo;
use Agency\Contracts\ImageInterface;

class ImageRepository extends Repository implements ImageRepositoryInterface {

	public function __construct(ImageInterface $image)
	{
		$this->image = $this->model = $image;
	}

	public function create( Photo $original,
                            Photo $thumbnail,
                            Photo $small,
                            Photo $square)
	{
		$unique_id = uniqid();

		$original_image = $this->image->create([
			'url'    => $original->url,
			'preset' => $this->image->presetType('original'),
			'guid'   => $unique_id
		]);

		$this->image->create([
			'url'    => $thumbnail->url,
			'preset' => $this->image->presetType('thumbnail'),
			'guid'   => $unique_id
		]);

		$this->image->create([
			'url'    => $small->url,
			'preset' => $this->image->presetType('small'),
			'guid'   => $unique_id
		]);


		$this->image->create([
			'url'    => $square->url,
			'preset' => 'square',
			'guid'   => $unique_id
		]);

		return $original_image;
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

    public function groupDelete($images_id)
    {
    	return $this->image->destroy($images_id);
    }

}
