<?php namespace Agency\Api\Mappers;

use Agency\Api\TagsCollection;

use Agency\Cms\Tag;

class TagMapper{

	protected $tag;

	protected $tags_collection;

	public function make($tags)
	{
		
		$this->tags_collection = new TagsCollection();
		foreach ($tags as $tag) {
			$this->tags_collection->push($this->parseAndFill($tag));
		}
		return $this->tags_collection;
	}

	public function parseAndFill($tag)
	{
		$this->tag['text'] = $tag->text;
		$this->tag['slug'] = $tag->slug;
		
		return $this->tag;
	}
	
}