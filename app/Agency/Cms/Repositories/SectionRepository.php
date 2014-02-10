<?php namespace Agency\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Section;

class SectionRepository extends Repository implements Contracts\SectionRepositoryInterface {

    /**
     * Represents the default sections
     * that every admin should have access to,
     * must be at least one.
     *
     * @var array
     */
    protected $defaults = ['dashboard'];

    public function __construct(Section $section)
    {
        $this->model = $this->section = $section;
    }

    /**
     * Create a new Section.
     *
     * @param  string  $title
     * @param  string  $alias
     * @param  string  $icon
     * @param  integer  $parent_id
     * @param  boolean $is_fertile
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($title, $alias = '', $icon, $parent_id, $is_fertile = false, $is_roleable = false)
    {
        return $this->section->create(compact('title', 'alias', 'icon', 'parent_id', 'is_fertile', 'is_roleable'));
    }

    /**
     * Update a section
     * @param  string $title
     * @param  string $alias
     * @param  string $icon
     * @param  integer $parent_id
     * @param  boolean $is_fertile
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id, $title, $alias, $icon, $parent_id, $is_fertile, $is_roleable)
    {
        // find the record (fails with an exception when not found)
        $section = $this->find($id);
        // fill the attributes - NB: Everything you fill in here must be set in the 'fillable'
        $section->fill(compact('title', 'alias', 'icon', 'parent_id', 'is_fertile', 'is_roleable'));
        // save modifications
        $section->save();

        return $section;
    }

    /**
     * Return the sections that
     * are allowed to have roles
     * set on them.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function roleable()
    {
        return $this->section->where('is_roleable', true)->get();
    }

    /**
     * Returns the default sections
     * that are allowed for every Authorable entity instance.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function initial($additional = [])
    {
        return $this->section->whereIn('alias', array_merge($this->defaults,$additional))->get();
    }
}