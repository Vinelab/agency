<?php  namespace Agency\Repositories\Contracts;

interface TagRepositoryInterface {

	/**
	 * create a new tag
	 *
	 * @param {string} $text
	 */
	public function create($text);

	/**
	 * create multiple tags
	 * 
	 * @param  array $texts 
	 * @return Illuminate\Database\Elquent\Collection of created tags ids
	 */
	public function splitFound($texts);

}
