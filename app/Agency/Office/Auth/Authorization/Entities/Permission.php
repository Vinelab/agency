<?php namespace Agency\Office\Auth\Authorization\Entities;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

class Permission extends Entity {

    protected $label = ['Permission', 'Cms'];

    protected $fillable = ['title', 'alias', 'description'];

    public function roles()
    {
        return $this->belongsToMany('Agency\Office\Auth\Authorization\Entities\Role', 'cms_role_permission')
            ->withTimestamps();
    }
}
