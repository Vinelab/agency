<?php namespace Agency\Office\Auth\Authorization\Entities;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use NeoEloquent;

use Agency\Contracts\Office\PrivilegeEntityInterface;

class Privilege extends NeoEloquent implements PrivilegeEntityInterface {

    protected $label = ['Privilege', 'Cms'];

    protected $fillable = [];

    public function resource()
    {
        return $this->morphTo();
    }

    public function role()
    {
        return $this->hasOne('Agency\Office\Auth\Authorization\Entities\Role', 'AS');
    }

    public function section()
    {
        return $this->hasOne('Agency\Office\Section', 'ON');
    }

    public function artistSection()
    {
        return $this->hasOne('Agency\Artists\Section', 'ON');
    }

    public function admin()
    {
        return $this->belongsTo('Agency\Office\Admin', 'GRANTED');
    }

    public function by()
    {
        return $this->belongsTo('Agency\Office\Admin', 'BY');
    }

    public function delete()
    {
        // Delete the edges so that we can delete the node.
        $this->role()->edge($this->role)->delete();
        if ($section = $this->section) $this->section()->edge($section)->delete();
        if ($artist_section = $this->artistSection) $this->artistSection()->edge($artist_section)->delete();
        $this->admin()->edge($this->authorable)->delete();

        parent::delete();
    }

}
