<?php  namespace Agency\Repositories\Contracts;

interface TagRepositoryInterface {

	/**
	 * create a new tag
	 *
	 * @param {string} $text
	 */
	public function create($text);

}
