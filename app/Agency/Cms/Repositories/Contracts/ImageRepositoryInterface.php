<?php namespace Agency\Cms\Repositories\Contracts;

interface ImageRepositoryInterface {

	public function create($url);

	public function assignImageToPost($image,$post);

	public function storeTemp($images);

	public function deleteTemp($image);


}