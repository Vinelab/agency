<?php namespace Agency\Repositories\Contracts;

/**
 * @author Adib Hanna <adib@vinelab.com>
 */

interface RepositoryInterface {

	public function find($id);

	public function findBy($attribute, $value);

	public function fill($attributes);

	public function fillAndSave($attributes);

	public function delete($id);
}