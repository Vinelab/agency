<?php namespace Agency\Media\Temp;


use Agency\Media\Temp\Contracts\TemporaryInterface;

class Temporary implements TemporaryInterface {

	public function storeImage($image)
	{
		$url = time().".".$image->getClientOriginalName();
		$image->move('tmp/',$url);
		return $url;
	}
}