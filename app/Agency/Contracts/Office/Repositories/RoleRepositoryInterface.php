<?php namespace Agency\Contracts\Cms\Repositories;

interface RoleRepositoryInterface {

	/**
	 * Return the artist's roles.
	 * @param array $ids
	 * @return mixed
	 */

    public function create($title, $alias);

    public function update($id, $title, $alias);

    public function updatePermissions($id, $permission_ids);

    public function allWithPermissions();

}
