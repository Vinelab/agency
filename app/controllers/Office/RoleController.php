<?php namespace Agency\Cms\Controllers;

use Input, Response, Redirect, Lang;
use Agency\Cms\Exceptions\UnauthorizedException;
use Agency\Contracts\Cms\Repositories\RoleRepositoryInterface as Roles;
use Agency\Contracts\Cms\Validators\RoleValidatorInterface as RoleValidator;

class RoleController extends Controller {

    /**
     * The role repository instance.
     *
     * @var Agency\Contracts\Cms\Repositories\RoleRepositoryInterface
     */
    protected $roles;

    /**
     * The role validator instance.
     *
     * @var Agency\Cms\Validator\Contracts\RoleValidatorInterface
     */
    protected $validator;

    public function __construct(Roles $roles, RoleValidator $validator)
    {
        parent::__construct();

        $this->roles = $roles;
        $this->validator = $validator;
    }

    public function index()
    {
        return $this->roles->allWithPermissions();
    }

    public function create()
    {

    }

    public function store()
    {
        if (Auth::hasPermission('create'))
        {
            try {

                $this->validator->validate(Input::get());

                $role = $this->roles->createWithPermissions(Input::get('title'), Input::get('alias'), Input::get('permissions'));

                return $role;

            } catch (\Agency\Cms\Exceptions\InvalidRoleException $e) {

                return Response::json(['error' => $e->messages()]);
            }
        }
    }

    public function edit($id)
    {
        if (Auth::hasPermission('update'))
        {
            return $this->roles->find($id);
        }

        throw new UnauthorizedException;
    }

    public function update($id)
    {
        if (Auth::hasPermission('update'))
        {
            try {

                $this->validator->validate(Input::get());

                // update role info
                $updated = $this->roles->update($id, Input::get('title'), Input::get('alias'));
                // update role permissions
                $this->roles->updatePermissions(Input::get('id'), Input::get('permissions'));

                return Response::json($updated);

            } catch (\Agency\Cms\Exceptions\InvalidRoleException $e) {
                return Response::json(['error' => $e->messages()], 400);
            }
        }


        throw new UnauthorizedException;
    }

    public function destroy($id)
    {
        if (Auth::hasPermission('delete'))
        {
            $this->roles->remove($id);
        }
    }
}
