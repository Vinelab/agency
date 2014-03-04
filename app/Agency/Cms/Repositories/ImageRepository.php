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

	public function create($response)
	{
		$original = $response['original'];
		$hashed_id = md5($original->id.time());

		$this->image=$this->image->create(["url"=>$original->url,"preset"=>"original","photo_id"=>$hashed_id]);
		
		$small = $response['small'];
		$this->image->create(["url"=>$small->url,"preset"=>"small","photo_id"=>$hashed_id]);
		
		$thumbnail = $response['thumbnail'];
		$this->image->create(["url"=>$thumbnail->url,"preset"=>"thumbnail","photo_id"=>$hashed_id]);
		
		$square = $response['square'];
		$this->image->create(["url"=>$square->url,"preset"=>"square","photo_id"=>$hashed_id]);
		
		return $this->image;
	}

	public function assignImageToPost($image,$post)
	{
		$media=$image->media()->create(["post_id"=>$post->id]);
		return $media;
	}

	public function detachImageFromPost($image,$post)
	{
		try {
			
			return $post->media()->where('media_type','=','Agency\Cms\Image')->where('media_id','=',$image)->first()->delete();

		} catch (Exception $e) {
			
			return Response::json(['message'=>$e->getMessage()]);
		}

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

	public function delete($id)
	{
		try {

			$image = Image::find($id);
			$image->delete();

		} catch (Exception $e) {
			
			return Response::json(['messages'=>$e->getMessages()]);
		}

	}

	public function getThumbnail($photo_id)
	{
		return $this->image->where('photo_id','=',$photo_id)->where('preset','=','thumbnail')->first();

	}




}