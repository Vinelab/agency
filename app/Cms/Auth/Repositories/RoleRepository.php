<?php namespace Agency\Cms\Auth\Repositories;

use Agency\Cms\Repositories\Repository;
use Agency\Cms\Auth\Authorization\Entities\Role;
use Agency\Contracts\Cms\Repositories\RoleRepositoryInterface;

class RoleRepository extends Repository implements RoleRepositoryInterface {

    /**
     * The Role entity instance.
     *
     * @var Agency\Cms\Auth\Authorization\Entities\Role
     */
    protected $role;

    public function __construct(Role $role)
    {
        $this->model = $this->role = $role;
    }

	/**
	 * Return the artist's roles.
	 * @param array $ids
	 * @return mixed
	 */
	public function forArtists($ids = [])
	{
		if(empty($ids))
		{
			return $this->role->where('for_artists', true)->get();
		}

		return $this->role->whereIn($ids)->get();
	}


    public function create($title, $alias, $for_artists = false)
    {
        return $this->role->create(compact('title', 'alias', 'for_artists'));
    }

    public function createWithPermissions($title, $alias, $permissions)
    {
        return $this->role->createWith(compact('title', 'alias'), compact('permissions'));
    }

    public function update($id, $title, $alias, $for_artists = false)
    {
        $role = $this->find($id);

        $role->fill(compact('title', 'alias', 'for_artists'));

        $role->save();

        return $role;
    }

    public function updatePermissions($id, $permission_ids)
    {
        $role = $this->find($id);

        // Sync permissions using ids
        $role->permissions()->sync(explode(',', $permission_ids));

        return $role;
    }

    public function allWithPermissions()
    {
        return $this->role->with('permissions')->where('for_artists', null)->get();
    }

    public function allWithArtistsPermissions()
    {
        return $this->role->with('permissions')->where('for_artists', true)->get();
    }
}
