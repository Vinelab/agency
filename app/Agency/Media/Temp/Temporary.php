<?php namespace Agency\Media\Temp;


use Agency\Media\Temp\Contracts\TemporaryInterface;

class Temporary implements TemporaryInterface {

	public function storeImage($image)
	{
		$url = md5(microtime(true).$image->getClientOriginalName());
		$image->move('tmp/',$url);
		return $url;
	}
}