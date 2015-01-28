<?php namespace Agency\Contracts\Api; 

interface CodeManagerInterface {

	public function get($key);

	public function store($key, $value);

	public function remove($key);

}