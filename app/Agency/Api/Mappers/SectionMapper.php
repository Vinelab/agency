<?php namespace Agency\Api\Mappers;

use Agency\Api\SectionsCollection;

use Agency\Office\Section;

class SectionMapper{

	protected $section;

	protected $sections_collection;

	

	public function make($sections)
	{
		$this->sections_collection = new SectionsCollection();
		foreach ($sections as $section) {
			$this->sections_collection->push($this->parseAndFill($section));
		}

		return $this->sections_collection;
	}

	public function parseAndFill(Section $section)
	{
		$this->section['title'] = $section->title;
		$this->section['slug'] = $section->alias;
		return $this->section;
	}


}