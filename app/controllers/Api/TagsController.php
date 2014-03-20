<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;

use Input, Response, File, DB, Lang;

use Agency\Cms\Section;

use Agency\Api\Mappers\TagMapper;

class TagsController extends \Controller {

    public function __construct( TagRepositoryInterface $tag)
    {
        $this->tag = $tag;
        $this->tagMapper = new TagMapper();

    }

    public function index()
    {
        $tags = $this->tag->all();
        if(!$tags->isEmpty())
        {
            return dd($this->tagMapper->make($tags)->toArray());
        }

    }
}
