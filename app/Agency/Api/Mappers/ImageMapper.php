<?php namespace Agency\Api\Mappers;

use Agency\Api\imagesCollection;

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
		$this->image['original'] = $image->presetUrl('original');
		$this->image['small'] = $image->presetUrl('small');
		$this->image['thumbnail'] = $image->presetUrl('thumbnail');
		$this->image['square'] = $image->presetUrl('square');

		return $this->image;
	}
	
}