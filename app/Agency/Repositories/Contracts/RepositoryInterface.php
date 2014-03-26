<?php namespace Agency\Repositories\Contracts;

/**
 * @author Adib Hanna <adib@vinelab.com>
 */

interface RepositoryInterface {

    public function first();

    public function all();

    public function find($id);

    public function findBy($attribute, $value, $relations = null);

    public function fill($attributes);

    public function fillAndSave($attributes);

    public function remove($id);
}
