<?php namespace ContentManagementSystem\Cms\Repositories\Contracts;

interface ImagesRepositoryInterface {

	public function create($url);

	public function assignImageToPost($image,$post);

	public function storeTemp($images);

	public function deleteTemp($image);


}