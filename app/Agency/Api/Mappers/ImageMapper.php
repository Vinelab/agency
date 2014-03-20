<?php namespace Agency\Api\Mappers;

use Agency\Api\ImagesCollection;

use Agency\Cms\Image;

class ImageMapper{

	protected $image;

	protected $imagesCollection;

	public function make($images)
	{
		$this->imagesCollection = new ImagesCollection();
		foreach ($images as $image) {
			$this->imagesCollection->push($this->parseAndFill($image));
		}
		return $this->imagesCollection;
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