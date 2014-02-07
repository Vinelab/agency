<?php namespace Najem\Cms\Authority\Entities;

use Eloquent;
use Agency\Helper;

class Entity extends Eloquent {

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

    public function dbTable()
    {
        return $this->table;
    }
}