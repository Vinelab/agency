<?php namespace  Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\ImageRepositoryInterface;


use DB;
use Agency\Cms\Image;

use File;



class ImageRepository extends Repository implements ImageRepositoryInterface {

	public function __construct(Image $image)
	{
		$this->image = $this->model = $image;
	}

	public function create($url)
	{
		$this->image=$this->image->create(compact("url"));
		return $this->image;
	}

	public function assignImageToPost($image,$post)
	{
		$media=$image->media()->create(["post_id"=>$post->id]);
		return $media;
	}

	public function storeTemp($images)
	{
		$images_url=[];
		foreach ($images as $image) {
			$url = time().".".$image->getClientOriginalName();
			$image->move('tmp/',$url);
			array_push($images_url, $url);
		}
		
		return $images_url;
	}

	public function deleteTemp($url)
	{
	 	$file = basename($url);

		$base = base_path().'/public/tmp/';
		unlink($base.$file);
	}




}