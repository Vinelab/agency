<?php namespace Agency\Office\Auth\Authorization\Entities;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

class Role extends Entity {

    protected $label = ['Role', 'Cms'];

    protected $fillable = ['title', 'alias', 'for_artists'];

    public function permissions()
    {
        return $this->hasMany('Agency\Office\Auth\Authorization\Entities\Permission', 'PERMISSION');
    }

    public function admin()
    {
        return $this->belongsToMany('Agency\Office\Admin', 'admin_roles')->withTimestamps();
    }

    public function resource()
    {
        return $this->morphTo();
    }
}
