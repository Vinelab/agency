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
		$slug = Helper::slugify($text, $this->tag);
		$this->tag = $this->tag->firstOrCreate(compact("text","slug"));
		return $this->tag;
	}

	public function splitFound($tags)
	{
		// generate slugs
		$tags = array_map(function($text) {
			$slug = Helper::slugify($text, $this->tag);
			return compact('text', 'slug');
		}, $tags);

		// extract existing tags
		$slugs = array_map(function($tag){
			return $tag['slug'];
		}, $tags);

		$existing = $this->tag->whereIn('slug', $slugs)->get();
		$existing_slugs = $existing->lists('slug');


		$new_tags = array_filter($tags, function($tag) use($existing_slugs) {
			return ! in_array($tag['slug'], $existing_slugs);
		});

		// create tag models out of the new ones
		$new_tags = array_map(function($tag){
			return new Tag($tag);
		}, $new_tags);


		$existing = $existing->lists('id');

		return [
			'new' => $new_tags,
			'existing' => $existing
		];
	}

}
