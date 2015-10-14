<?php namespace Agency\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use NeoEloquent;
use Agency\Helper;

use Agency\Contracts\Cms\PrivilegableInterface;

class Section extends NeoEloquent implements PrivilegableInterface {

    protected $label = ['Section', 'Agency', 'Cms'];

    protected $fillable = ['title', 'alias', 'icon', 'is_fertile', 'is_roleable'];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model){

            if ( ! isset($model->alias) || empty($model->alias))
            {
                $model->alias = Helper::aliasify($model->title);
            }

        });
    }

    public function getKey()
    {
        return $this->id;
    }

    public function getKeyName()
    {
        return "id";
    }

    public function privileges()
    {
        return $this->morphMany('Agency\Cms\Auth\Authorization\Entities\Privilege', 'resource');
    }

    public function children()
    {
        return $this->hasMany('Agency\Cms\Section', 'PARENT_OF');
    }

    public function parent()
    {
        return $this->belongsTo('Agency\Cms\Section', 'PARENT_OF');
    }

    public function alias()
    {
        return $this->alias;
    }

    public function posts()
    {
        return $this->hasMany('Agency\Post','POST');
    }

    public function news()
    {
        return $this->hasMany('Agency\News','NEWS');
    }

    public function episodes()
    {
        return $this->hasMany('Agency\Episode','EPISODE');
    }


}
