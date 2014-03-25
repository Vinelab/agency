<?php namespace Agency\Cms\Controllers;

use Input;

use Agency\Repositories\Contracts\SectionRepositoryInterface as Sections;
use Agency\Cms\Repositories\Contracts\PermissionRepositoryInterface as Permissions;
use Agency\Cms\Validators\Contracts\PermissionValidatorInterface as PermissionValidator;

use Agency\Cms\Exceptions\UnauthorizedException;

class PermissionController extends Controller {

    /**
     * The permissions repo instance.
     *
     * @var Agency\Cms\Repositories\PermissionRepository
     */
    protected $permissions;

    /**
     * The Permission validator instance.
     *
     * @var Agency\Cms\Validators\Contracts\PermissionValidatorInterface
     */
    protected $validator;

    public function __construct(Sections $sections,
                                Permissions $permissions,
                                PermissionValidator $validator)
    {
        parent::__construct($sections);

        $this->permissions = $permissions;

        $this->validator = $validator;
    }

    public function index()
    {
        return $this->permissions->all();
    }

    public function show($id)
    {
        return $this->permissions->find($id);
    }

    public function create()
    {

    }

    public function store()
    {
        if ($this->admin_permissions->has('create'))
        {
            $this->validator->validate(Input::get());

            $permission = $this->permissions->create(Input::get('title'),
                                                    Input::get('alias'),
                                                    Input::get('description'));

            return $permission;
        }

        throw new UnauthorizedException;
    }

    public function edit($id)
    {
        if ($this->admin_permissions->has('update'))
        {
            return $this->permissions->find($id);
        }

        throw new UnauthorizedException;
    }

    public function update($id)
    {
        if ($this->admin_permissions->has('update'))
        {
            return $this->permissions->update($id,
                                            Input::get('title'),
                                            Input::get('alias'),
                                            Input::get('description'));
        }

        throw new UnauthorizedException;
    }

    public function destroy($id)
    {
        if ($this->admin_permissions->has('delete'))
        {
            $this->permissions->remove($id);
        }
    }
}