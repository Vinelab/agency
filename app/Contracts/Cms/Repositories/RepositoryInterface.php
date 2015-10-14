<?php namespace Agency\Contracts\Cms\Repositories;

interface RepositoryInterface {

    public function first();

    public function all();

    public function find($id, $relations = null);

    public function findBy($attribute, $value, $relations = null);

    public function fill($attributes);

    public function fillAndSave($attributes);

    public function remove($id);
}
