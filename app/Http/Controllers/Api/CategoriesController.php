<?php namespace Agency\Http\Controllers\Api;

use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Api\Repositories\Contracts\CodeRepositoryInterface;
use Agency\Http\Controllers\Controller;
use Input, Response, File, DB, Lang;
use Agency\Section;
use Agency\Api\Mappers\SectionMapper;
use Agency\Api\SectionsCollection;

class CategoriesController extends Controller
{

    /**
     * @param \Agency\Repositories\Contracts\SectionRepositoryInterface  $section
     * @param \Agency\Api\Repositories\Contracts\CodeRepositoryInterface $code
     */
    public function __construct(
        SectionRepositoryInterface $section,
        CodeRepositoryInterface $code
    ) {
        $this->section = $section;
        $this->code = $code;
        $this->sectionMapper = new SectionMapper();
        $this->sectionsCollection = new SectionsCollection();
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if ($this->code->findBy("code", Input::get('code'))) {
            $sections = $this->section->all();
            if (!$sections->isEmpty()) {
                return $this->sectionMapper->make($sections)->toArray();
            }
        } else {
            return Response::json(['status' => 400, 'messages' => Lang::get("messages.invalid_code")]);
        }
    }
}
