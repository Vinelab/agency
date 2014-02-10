<?php namespace  ContentManagementSystem\Cms\Repositories;

use ContentManagementSystem\Cms\Repositories\Contracts\ImagesRepositoryInterface;


use DB;
use ContentManagementSystem\Cms\Image;

use File;



class ImageRepository implements ImagesRepositoryInterface {

	public function __construct(Image $image)
	{
		$this->image=$image;
	}
	
	protected $rules=[
		"url"=>"required"
	];

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