<?php namespace Agency\Mappers;

use Agency\Api\ImagesCollection;

use Agency\Cms\Image;

class ImageMapper{

	protected $image;

	protected $images_collection;

	public function make($images)
	{
		$this->images_collection = new imagesCollection();
		foreach ($images as $image) {
			$this->images_collection->push($this->parseAndFill($image));
		}
		return $this->images_collection;
	}

	public function parseAndFill($image)
	{

		$default_cover = 'https://s3.amazonaws.com/season10%2Fartists%2Fwebs/5411d23590cf5.jpeg';

		$this->image['original'] = isset($image->original)? $image->original : $default_cover;
		$this->image['small'] = isset($image->small)? $image->small : $default_cover;
		$this->image['thumbnail'] = isset($image->thumbnail) ? $image->thumbnail : $default_cover;
		$this->image['square'] = isset($image->square)? $image->square : $default_cover;

		return $this->image;
	}

}
