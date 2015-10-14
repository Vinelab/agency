<?php namespace Agency\Api;

use Illuminate\Support\Collection;

class PostsCollection extends Collection {

	protected $page;

	protected $total;

	public function setPage($page)
	{
		$this->page = $page;
	}

	public function setTotal($total)
	{
		$this->total = $total;
	}

	public function total()
	{
		return $this->total;
	}

	public function page()
	{
		return $this->page;
	}
}
