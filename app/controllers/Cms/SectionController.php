<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Response, Input, Auth, Authority, Lang;

use Agency\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;

use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Contracts\HelperInterface;
use Agency\Cms\Authentication\Contracts\AdminAuthorizerInterface;

class SectionController extends Controller {

    /**
     * The section validator instance.
     *
     * @var Agency\Validators\SectionValidator
     */
    protected $validator;

    /**
     * Provider of authorization related
     * tasks.
     *
     * @var Agency\Cms\Authentication\Contracts\AdminAuthorizerInterface
     */
    protected $authorizer;

    public function __construct(SectionRepositoryInterface $sections,
                                SectionValidator $validator,
                                HelperInterface $helper,
                                AdminAuthorizerInterface $authorizer)
    {
        parent::__construct($sections);

        $this->validator = $validator;
        $this->helper = $helper;
        $this->authorizer = $authorizer;

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
        if (Auth::hasPermission('create'))
        {
           try {

                $is_fertile = Input::get('is_fertile') === 'Yes' ? 1 : 0;
                $is_roleable = Input::get('is_roleable') === 'Yes' ? 1 : 0;

                $attributes = Input::get();
                $attributes['is_fertile'] = $is_fertile;
                $attributes['is_roleable'] = $is_roleable;

                $this->validator->validate($attributes);

                $sections = $this->cms_sections['accessible'];

                $sections = $sections->map(function($section){

                    $privilege = $section->privileges()->first();

                    return  [$section->alias() => $privilege->role()->first()->alias()];

                },$sections);

                $sections = $sections->collapse()->toArray();

                $section = $this->sections->create(Input::get('title'),
                                                    Input::get('alias'),
                                                    Input::get('icon'),
                                                    Input::get('parent_id'),
                                                    $is_fertile,
                                                    $is_roleable);

                $sections[$section->alias] = 'admin'; 

                $admin = Auth::getUser();

                $this->authorizer->authorize($admin, array_filter($sections));



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