<?php  namespace Agency\Repositories;

use DB;
use Agency\Tag;
use Agency\Helper;
use Agency\Contracts\Repositories\TagRepositoryInterface;
use Agency\Contracts\HelperInterface;

class TagRepository extends Repository implements TagRepositoryInterface {

	/**
	 * the tag model
	 *
	 * @var Agency\Tag
	 */
	protected $tag;

	public function __construct(Tag $tag,
								HelperInterface $helper)
	{
		$this->tag = $this->model = $tag;
		$this->helper = $helper;
	}

	public function create($text, $profile_id = Null)
	{
		$slug = $this->helper->slugify($text, $this->tag);
		$this->tag = $this->tag->firstOrCreate([
			"text" => $text,
			"slug" => $slug,
			"profile_id" => $profile_id
		]);
		return $this->tag;
	}

	public function update($text, $profile_id = Null)
	{
		$tag = $this->findBy('profile_id',$profile_id);
		$slug = $this->helper->slugify($text, $this->tag);
		return $tag->update([
			'text' => $text,
			'slug' => $slug,
			'profile_id' => $profile_id
		]);

	}

	public function splitFound($tags)
	{
		// generate slugs
		$tags = array_map(function($text) {
			$slug = $this->helper->slugify($text);
			return ['text' => $text, 'slug' => $slug];
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
			return $this->tag->create($tag);
		}, $new_tags);

		$tags_ids = $existing->merge($new_tags);

		return $tags_ids->lists('id');

	}

}
