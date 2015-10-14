<?php namespace Agency\Http\Controllers\Cms;

use Input;
use Agency\Cms\Exceptions\UnauthorizedException;
use Agency\Contracts\Cms\Repositories\PermissionRepositoryInterface as Permissions;
use Agency\Contracts\Cms\Validators\PermissionValidatorInterface as PermissionValidator;

class PermissionController extends Controller {

    /**
     * The permissions repo instance.
     *
     * @var Agency\Cms\Auth\Repositories\PermissionRepository
     */
    protected $permissions;

    /**
     * The Permission validator instance.
     *
     * @var Agency\Contracts\Cms\Validators\PermissionValidatorInterface
     */
    protected $validator;

    public function __construct(Permissions $permissions,
                                PermissionValidator $validator)
    {
        parent::__construct();

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
        if (Auth::hasPermission('create'))
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
        if (Auth::hasPermission('update'))
        {
            return $this->permissions->find($id);
        }

        throw new UnauthorizedException;
    }

    public function update($id)
    {
        if (Auth::hasPermission('update'))
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
        if (Auth::hasPermission('delete'))
        {
            $this->permissions->remove($id);
        }
    }
}
