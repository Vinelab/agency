<?php namespace Agency\Cms\Authority\Entities;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

class Permission extends Entity {

    protected $table = 'cms_permissions';

    protected $fillable = ['title', 'alias', 'description'];

    public function roles()
    {
        return $this->belongsToMany('Agency\Cms\Authority\Entities\Role', 'cms_role_permission')
            ->withTimestamps();
    }
}