<?php namespace Xfactor\Contracts\Services;

interface PhotosServiceInterface{

	public function upload();

	public function aws();

	public function prepareResponse($uploadedObj);

	public function parse($data);

}
