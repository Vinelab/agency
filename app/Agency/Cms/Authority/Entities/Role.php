<?php namespace Agency\Cms\Authority\Entities;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

class Role extends Entity {

    protected $table = 'cms_roles';

    protected $fillable = ['title', 'alias'];

    public function permissions()
    {
        return $this->belongsToMany('Agency\Cms\Authority\Entities\Permission', 'cms_role_permissions')
            ->withTimestamps();
    }

    public function admin()
    {
        return $this->belongsToMany('Agency\Cms\Admin', 'admin_roles')->withTimestamps();
    }

    public function resource()
    {
        return $this->morphTo();
    }

    public function alias()
    {
        return $this->alias;
    }
}