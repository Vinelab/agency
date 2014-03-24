<?php namespace Agency\Cms\Controllers;

use Input, Response, Redirect, Lang;

use Agency\Cms\Repositories\Contracts\RoleRepositoryInterface as Roles;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface as Sections;
use Agency\Cms\Validators\Contracts\RoleValidatorInterface as RoleValidator;

use Agency\Cms\Exceptions\UnauthorizedException;

class RoleController extends Controller {

    /**
     * The role repository instance.
     *
     * @var Agency\Cms\Repositories\Contracts\RoleRepositoryInterface
     */
    protected $roles;

    /**
     * The role validator instance.
     *
     * @var Agency\Cms\Validator\Contracts\RoleValidatorInterface
     */
    protected $validator;

    public function __construct(Sections $sections, Roles $roles, RoleValidator $validator)
    {
        parent::__construct($sections);

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
        if ($this->admin_permissions->has('create'))
        {
            try {

                $this->validator->validate(Input::get());

                $role = $this->roles->create(Input::get('title'), Input::get('alias'));

                $this->roles->updatePermissions($role->getKey(), Input::get('permissions'));

                return $role;

            } catch (\Agency\Cms\Exceptions\InvalidRoleException $e) {

                return Response::json(['error' => $e->messages()]);
            }
        }
    }

    public function edit($id)
    {
        if ($this->admin_permissions->has('update'))
        {
            return $this->roles->find($id);
        }

        throw new UnauthorizedException;
    }

    public function update($id)
    {
        if ($this->admin_permissions->has('update'))
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
        if ($this->admin_permissions->has('delete'))
        {
            $this->roles->remove($id);
        }
    }
}