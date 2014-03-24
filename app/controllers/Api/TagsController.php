<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;
use Agency\Api\Repositories\Contracts\CodeRepositoryInterface;

use Input, Response, File, DB, Lang, Controller;

use Agency\Cms\Section;

use Agency\Api\Mappers\TagMapper;

class TagsController extends Controller {

    public function __construct(    TagRepositoryInterface $tag,
                                    CodeRepositoryInterface $code)
    {
        $this->tag = $tag;
        $this->tagMapper = new TagMapper();
        $this->code = $code;

    }

    public function index()
    {
        if($this->code->findBy("code",Input::get('code')))
        {
            $tags = $this->tag->all();
            if(!$tags->isEmpty())
            {
                return $this->tagMapper->make($tags)->toArray();
            }
        } else {
            return Response::json(['status'=>400,'messages'=>Lang::get("messages.invalid_code")]);
        }
    }
}
