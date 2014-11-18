<?php namespace Agency\Office\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Response, Input, Auth, Lang;
use Agency\Office\Validators\SectionValidator;
use Agency\Office\Exceptions\UnauthorizedException;
use Agency\Contracts\Office\Repositories\ArtistRepositoryInterface;
use Agency\Contracts\Office\Repositories\SectionRepositoryInterface as Sections;

class SectionController extends Controller {

    /**
     * The section validator instance.
     *
     * @var Agency\Office\Validators\SectionValidator
     */
    protected $validator;

    /**
     * @var \Agency\Contracts\Office\Repositories\SectionRepositoryInterface
     */
    protected $sections;

    public function __construct(SectionValidator $validator, Sections $sections)
    {
        parent::__construct();

        $this->sections = $sections;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the sections.
     *
     * @return Response
     */
    public function index()
    {
        return $this->sections->allWithParent();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (Auth::hasPermission('create'))
        {
            $attributes = $this->filterAndValidate();

            $section = $this->sections->create($attributes['title'],
                                                $attributes['alias'],
                                                $attributes['icon'],
                                                $attributes['is_fertile'],
                                                $attributes['is_roleable']);

            if (Input::has('parent') && Input::get('parent') > 0)
            {
                $section->parent()->associate($this->sections->find(Input::get('parent')))->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $alias
     * @return Response
     */
    public function show($alias)
    {
        return $this->sections->findByAlias($alias);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if (Auth::hasPermission('update'))
        {
            return $this->sections->find($id);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        if (Auth::hasPermission('update'))
        {
            try {

                $attributes = $this->filterAndValidate();

                $updated = $this->sections->update($id,
                                            $attributes['title'],
                                            $attributes['alias'],
                                            $attributes['icon'],
                                            $attributes['is_fertile'],
                                            $attributes['is_roleable']);

                if (Input::has('parent') && Input::get('parent') > 0)
                {
                    // Only when we receive a Section's Id we proceed with setting it as a parent
                    // bcz sometimes when updating other section info we'll get the parent title instead.
                    if (is_numeric($attributes['parent'])) $updated->parent()->associate($this->sections->find($attributes['parent']))->save();
                }

                return Response::json($updated);

            throw new UnauthorizedException;

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

                return Response::json(['error' => Lang::get('errors.section_not_found')], 404);

            } catch (\Exception $e) {

                return Response::json(['error' => Lang::get('errors.unexpected')], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Auth::hasPermission('delete'))
        {
            try {
                $this->sections->remove($id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $error = Lang::get('errors.section_not_found');
                return Response::json(compact('error'), 404);
            }
        }
    }

    /**
     * Format the incoming input into db-friendly attributes and validate them.
     *
     * @return array The formatted attribtues.
     */
    protected function filterAndValidate()
    {
        try {

            $attributes = Input::get();
            $attributes['is_fertile']  = Input::get('is_fertile') === 'Yes' ? true : false;
            $attributes['is_roleable'] = Input::get('is_roleable') === 'Yes' ? true : false;

            $this->validator->validate($attributes);

            return $attributes;

         } catch (\Agency\Office\Exceptions\InvalidSectionException $e) {
            return Response::json(['error' => $e->messages()], 400);
        }
    }

}
