<?php  namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;
use DB;
use Agency\Cms\Tag;
use Agency\Helper;


class TagRepository extends Repository implements TagRepositoryInterface {


	public function __construct(Tag $tag)
	{
		$this->tag = $this->model = $tag;
	}

	public function create($text)
	{
		$slug = Helper::slugify($text);
		$this->tag = $this->tag->firstOrCreate(compact("text","slug"));
		return $this->tag;
	}
	
}