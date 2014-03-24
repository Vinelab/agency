<?php namespace Agency\Cms\Repositories\Contracts;

interface ImageRepositoryInterface {

	public function create($url);

	public function assignImageToPost($image,$post);

	public function detachImageFromPost($image,$post);

	public function delete($id);

	public function storeTemp($images);

	public function deleteTemp($image);

	public function getThumbnail($photo_id);

}