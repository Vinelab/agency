<?php namespace Agency\Media\Photos\Contracts;

interface StoreInterface {

	public function put($image);

	public function remove($image);

}