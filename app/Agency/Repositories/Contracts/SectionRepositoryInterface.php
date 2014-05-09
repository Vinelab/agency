<?php namespace Agency\Repositories\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface SectionRepositoryInterface {

    /**
     * create a new section
     *
     * @param {string} $title
     * @param {string} $alias
     * @param {string} $icon
     * @param {int|string} $parent_id
     * @param {boolean} $is_fertile
     * @param {boolean} $is_roleable
     *
     * @return Agency\Section
     */
    public function create($title, $alias = '', $icon, $parent_id, $is_fertile = false, $is_roleable = false);

    /**
     * update section info
     *
     * @param {int|string} $id
     * @param {string} $title
     * @param {string} $alias
     * @param {string} $icon
     * @param {int|string} $parent_id
     * @param {boolean} $is_fertile
     * @param {boolean} $is_roleable
     *
     * @return Agency\Section
     */
    public function update($id, $title, $alias, $icon, $parent_id, $is_fertile, $is_roleable);

    /**
     * return all the sections that
     * can have roles
     *
     * @return Illuminate\Database\Eloquent\Collection of Agency\Section
     */
    public function roleable();

    /**
     * return the sections that should
     * be granted access to initially
     * when first creating an Admin,
     * adding to them the $additional stuff
     *
     * @param {array} $additional
     * @return Illuminate\Database\Eloquent\Collection of Agency\Section
     */
    public function initial($additional = []);

    /**
     * return the children sections
     * that cannot have children (infertile)
     * according to the section by its $alias
     *
     * @param {string} $alias
     * @return Illuminate\Database\Eloquent\Collection of Agency\Section
     */
    public function infertile($alias);

    /**
     * get the children of a section
     * by its alias
     *
     * @param {string} $alias
     * @return Agency\Section with children in 'sections' key
     */
    public function children($alias);

    /**
     *
     */
    public function parentSections($alias,$current_parent_section_id);

    /**
    * 
    * return the cms parent sections ex ('dashboard', 'contents','admnistration',...)
    * @param  Illuminate\Database\Eloquent\Collection  $accessible_section
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function sections($accessible_section);
}
