<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;
use DB;
use Agency\Cms\Tag;

class TagRepository extends Repository implements TagRepositoryInterface {


	public function __construct(Tag $tag)
	{
		$this->tag = $this->model = $tag;
	}

	public function create($text)
	{
		$this->tag = $this->tag->firstOrCreate(compact("text"));
		return $this->tag;
	}

	
}