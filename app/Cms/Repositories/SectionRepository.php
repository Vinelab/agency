<?php namespace Agency\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Section;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Agency\Contracts\Cms\Repositories\SectionRepositoryInterface;
use Config;

class SectionRepository extends Repository implements SectionRepositoryInterface {

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
    public function create($title, $alias = '', $icon, $is_fertile = false, $is_roleable = false)
    {
        return $this->section->create(compact('title', 'alias', 'icon', 'is_fertile', 'is_roleable'));
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
    public function update($id, $title, $alias, $icon, $is_fertile, $is_roleable)
    {
        // find the record (fails with an exception when not found)
        $section = $this->find($id);
        // fill the attributes - NB: Everything you fill in here must be set in the 'fillable'
        $section->fill(compact('title', 'alias', 'icon', 'is_fertile', 'is_roleable'));
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

    /**
     * Get all the sections with their parents relationship.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function allWithParent()
    {
        return $this->section->with('parent')->get();
    }

    /**
     * Find a section by its Id or its alias.
     *
     * @param  int|string $idOrAlias
     * @return \Agency\Cms\Section
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If section not found.
     */
    public function findByIdOrAlias($idOrAlias)
    {
        try {

            $section = $this->find($idOrAlias);

        } catch (ModelNotFoundException $e)
        {
            $section = $this->findByAlias($idOrAlias);
        }

        if ( ! $section) { throw new ModelNotFoundException; }

        return $section;
    }

    public function infertile()
    {
        return $this->section->where('is_fertile', false)->get();
    }

    public function getRelatedPosts($id)
    {
        $section = $this->find($id);
        return $section->posts()->orderBy('publish_date','desc')->get();
        return $section->posts()->orderBy('publish_date','desc')->paginate(Config::get('posts.number_per_page'));
    }

     public function getMultipleSectionBySlug(array $slug, $relation)
    {
        return $this->section->with("$relation")->whereIn('alias',$slug)->get();
    }

    public function getIdsofMutlipleSections(Collection $sections)
    {
        $ids = [];
        foreach ($sections as $section) {
            if($section->is_fertile)
            {
                $children = $section->children;
                $children_ids = $children->lists('id');
                $ids = array_merge($children_ids,$ids);
            } else {
                array_push($ids, $section->id);
            }
        }

        return $ids;

    }
}
