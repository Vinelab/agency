<?php namespace Agency\Api\Mappers;

use Agency\Api\TagsCollection;

use Agency\Cms\Tag;

class TagMapper{

	protected $tag;

	protected $tagsCollection;

	public function make($tags)
	{
		
		$this->tagsCollection = new TagsCollection();
		foreach ($tags as $tag) {
			$this->tagsCollection->push($this->parseAndFill($tag));
		}
		return $this->tagsCollection;
	}

	public function parseAndFill($tag)
	{
		$this->tag['text'] = $tag->text;
		$this->tag['slug'] = $tag->slug;
		
		return $this->tag;
	}
	
}