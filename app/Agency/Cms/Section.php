<?php namespace Agency\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Eloquent;
use Agency\Helper;

use Agency\Cms\Authority\Contracts\PrivilegableInterface;
use Agency\Cms\Repositories\Contracts\LinkableInterface;

class Section extends Eloquent implements PrivilegableInterface,LinkableInterface {

    protected $table = 'cms_sections';

    protected $fillable = ['title', 'alias', 'icon', 'parent_id', 'is_fertile', 'is_roleable'];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model){

            if ( ! isset($model->alias) or empty($model->alias))
            {
                $model->alias = Helper::aliasify($model->title);
            }

        });
    }

    public function privileges()
    {
        return $this->morphMany('Agency\Cms\Authority\Entities\Privilege', 'resource');
    }

    public function identifier()
    {
        $key = $this->identifierKey();

        return $this->$key;
    }

    public function identifierKey()
    {
        return 'id';
    }

    public function sections()
    {
        return $this->hasMany('Agency\Cms\Section', 'parent_id');
    }

    public function alias()
    {
        return $this->alias;
    }

    public function getIdentifier()
    {
        return $this->id;
    }

    public function getInstance()
    {
        return $this;
    }

    public function linker()
    {
        return $this->morphMany("Agency\Cms\Linker", "linkable");
    }

}