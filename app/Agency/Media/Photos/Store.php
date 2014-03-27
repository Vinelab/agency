<?php namespace Agency\Media\Photos;


use Agency\Media\Photos\Contracts\StoreInterface;

class Store implements StoreInterface {

	protected $location;

	public function __construct()
	{
		$this->location ="tmp/";
	}


	public function put($image)
	{
		$url = md5(microtime(true).$image->getClientOriginalName());
		$image->move($this->location,$url);
		return $url;
	}

	public function remove($image_url)
	{
		$file = basename($image_url);

		$base = public_path()."/".$this->location;
		unlink($base.$file);
	}
	
}