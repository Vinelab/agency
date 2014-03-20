<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;

use Input, Response, File, DB, Lang;

use Agency\Cms\Section;

use Agency\Api\Mappers\SectionMapper;
use Agency\Api\SectionsCollection;

class CategoriesController extends \Controller {

    public function __construct( SectionRepositoryInterface $section)
    {
        $this->section = $section;
        $this->sectionMapper = new SectionMapper();
        $this->sectionsCollection = new SectionsCollection();
    }

    public function index()
    {
        $sections = $this->section->all();
        if(!$sections->isEmpty())
        {
            return dd($this->sectionMapper->make($sections)->toArray());
        }

    }
}
