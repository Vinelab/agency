<?php  namespace Agency\Repositories;

use DB;
use Agency\Tag;
use Agency\Helper;
use Agency\Repositories\Contracts\TagRepositoryInterface;


class TagRepository extends Repository implements TagRepositoryInterface {

	/**
	 * the tag model
	 *
	 * @var Agency\Tag
	 */
	protected $tag;

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
