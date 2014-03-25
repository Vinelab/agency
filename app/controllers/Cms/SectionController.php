<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Response, Input, Auth, Authority, Lang;

use Agency\Cms\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;

use Agency\Cms\Repositories\Contracts\ArtistRepositoryInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;

class SectionController extends Controller {

    /**
     * The section validator instance.
     *
     * @var Agency\Cms\Validators\SectionValidator
     */
    protected $validator;

    public function __construct(SectionRepositoryInterface $sections, SectionValidator $validator)
    {
        parent::__construct($sections);

        $this->validator = $validator;
    }

    /**
     * Display a listing of the sections.
     *
     * @return Response
     */
    public function index()
    {
        return $this->sections->all();
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
        if ($this->admin_permissions->has('create'))
        {
           try {

                $is_fertile = Input::get('is_fertile') === 'Yes' ? 1 : 0;
                $is_roleable = Input::get('is_roleable') === 'Yes' ? 1 : 0;

                $attributes = Input::get();
                $attributes['is_fertile'] = $is_fertile;
                $attributes['is_roleable'] = $is_roleable;

                $this->validator->validate($attributes);

                $this->sections->create(Input::get('title'),
                                        Input::get('alias'),
                                        Input::get('icon'),
                                        Input::get('parent_id'),
                                        $is_fertile,
                                        $is_roleable);

            } catch (\Agency\Cms\Exceptions\InvalidSectionException $e) {

                return Response::json(
                    ['error' => unserialize($e->getMessage())], 400);
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
        if ($this->admin_permissions->has('update'))
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
        if ($this->admin_permissions->has('update'))
        {
            try {

                $is_fertile = Input::get('is_fertile') === 'Yes' ? 1 : 0;
                $is_roleable = Input::get('is_roleable') === 'Yes' ? 1 : 0;

                $attributes = Input::get();
                $attributes['is_fertile'] = $is_fertile;
                $attributes['is_roleable'] = $is_roleable;

                $this->validator->validate($attributes);

                $updated = $this->sections->update($id,
                                                Input::get('title'),
                                                Input::get('alias'),
                                                Input::get('icon'),
                                                Input::get('parent_id'),
                                                $is_fertile,
                                                $is_roleable);

                return Response::json($updated);

            throw new UnauthorizedException;

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

                return Response::json(['error' => Lang::get('errors.section_not_found')], 404);

            } catch (\Agency\Cms\Exceptions\InvalidSectionException $e) {

                $error = ['messages' => unserialize($e->getMessage())];

                return Response::json(compact('error'), 400);

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
        if ($this->admin_permissions->has('delete'))
        {
            try {
                $this->sections->remove($id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $error = Lang::get('errors.section_not_found');
                return Response::json(compact('error'), 404);
            }
        }
    }

}