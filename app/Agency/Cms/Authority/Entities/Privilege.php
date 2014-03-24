<?php namespace Agency\Cms\Authority\Entities;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Eloquent;

class Privilege extends Eloquent {

    protected $table = 'cms_privileges';

    protected $fillable = ['role_id', 'admin_id', 'resource_id', 'resource_type'];

    public function dbTable()
    {
        return $this->table;
    }

    public function resource()
    {
        return $this->morphTo();
    }

    public function role()
    {
        return $this->belongsTo('Agency\Cms\Authority\Entities\Role');
    }
}