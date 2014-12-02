<?php namespace Agency\Contracts\Cms\Repositories;

interface RoleRepositoryInterface {

	/**
	 * Return the artist's roles.
	 * @param array $ids
	 * @return mixed
	 */
	public function forArtists($ids = []);

    public function create($title, $alias, $for_artists = false);

    public function update($id, $title, $alias, $for_artists = false);

    public function updatePermissions($id, $permission_ids);

    public function allWithPermissions();

    public function allWithArtistsPermissions();
}
