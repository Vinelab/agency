<?php namespace Agency\Contracts\Repositories;

/**
 * @author Adib Hanna <adib@vinelab.com>
 */

interface RepositoryInterface {

	public function find($id, $relations = null);

	public function findBy($attribute, $value, $relations = null);

	public function fill($attributes);

	public function fillAndSave($attributes);
}
