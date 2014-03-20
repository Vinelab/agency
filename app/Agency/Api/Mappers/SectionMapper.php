<?php namespace Agency\Api\Mappers;

use Agency\Api\SectionsCollection;

use Agency\Cms\Section;

class SectionMapper{

	protected $section;

	protected $sectionsCollection;

	

	public function make($sections)
	{
		$this->sectionsCollection = new SectionsCollection();
		foreach ($sections as $section) {
			$this->sectionsCollection->push($this->parseAndFill($section));
		}

		return $this->sectionsCollection;
	}

	public function parseAndFill(Section $section)
	{
		$this->section['title'] = $section->title;
		$this->section['slug'] = $section->alias;
		return $this->section;
	}


}