<?php namespace Agency\Cms\Auth\Authorization\Entities;

use NeoEloquent;
use Agency\Helper;

class Entity extends NeoEloquent {

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

    public function dbTable()
    {
        return $this->table;
    }
}
