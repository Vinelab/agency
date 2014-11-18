<?php namespace Agency\Contracts\Office\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface SectionRepositoryInterface {

	/**
     * Create a new Section.
     *
     * @param  string  $title
     * @param  string  $alias
     * @param  string  $icon
     * @param  boolean $is_fertile
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($title, $alias = '', $icon, $is_fertile = false, $is_roleable = false);

     /**
     * Update a section
     * @param  string $title
     * @param  string $alias
     * @param  string $icon
     * @param  boolean $is_fertile
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id, $title, $alias, $icon, $is_fertile, $is_roleable);

    /**
     * Return the sections that
     * are allowed to have roles
     * set on them.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function roleable();

    /**
     * Returns the default sections
     * that are allowed for every Authorable entity instance.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function initial($additional = []);

    /**
     * Get all the sections with their parents relationship.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function allWithParent();

    /**
     * Find a section by its Id or its alias.
     *
     * @param  int|string $idOrAlias
     * @return \Agency\Office\Section
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If section not found.
     */
    public function findByIdOrAlias($idOrAlias);
}
