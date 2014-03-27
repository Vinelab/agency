<?php  namespace Agency\Repositories\Contracts;

interface PostRepositoryInterface {

	/**
	 * create a new post
	 *
	 * @param string $title
	 * @param string $slug
	 * @param string $body
	 * @param int|string $user_id
	 * @param int|string $section
	 * @param DateTime $publish_date
	 * @param string $publish_state values must be specified at the model level and mapped to an enum
	 */
	public function create($title, $slug, $body, $user_id, $section, $publish_date, $publish_state);

	/**
	 * update a post's info
	 * specified by its $id
	 *
	 * @param int|string $id
	 * @param string $title
	 * @param string $slug
	 * @param string $body
	 * @param int|string $user_id
	 * @param int|string $section
	 * @param DateTime $publish_date
	 * @param string $publish_state values must be specified at the model level and mapped to an enum
	 */
	public function update($id,$title,$body,$user_id,$section,$publish_date,$publish_state,$slug);

	/**
	 * get a collection of posts
	 * by the specified ids
	 *
	 * @param array $ids
	 * @return Illuminate\Database\Eloquent\Collection of Agency\Post
	 */
	public function get($ids);

	/**
	 * @override
	 *
	 * remove a post by its id or slug,
	 * in addition to the parent method (by id only)
	 *
	 * @param int|string $id
	 * @return boolean
	 */
	public function remove($id);

	/**
	 * get the posts belonging
	 * to a section
	 *
	 * @param int|string $section_id
	 */
	public function forSection($section_id);

	/**
	 * find a post by its id or slug
	 *
	 * @param int|string $id_or_slug
	 * @return Agency\Post
	 */
	public function findByIdOrSlug($id_or_slug);

	/**
	 * return a unique slug out of
	 * the title
	 *
	 * @param string $title
	 * @return string
	 */
	public function uniqSlug($title);

	/**
	 * return all the published posts
	 * according with optional input
	 * parameters
	 *
	 * @param array $input
	 * @return Illuminate\Database\Eloquent\Collection of Agency\Post
	 */
	public function published($input = array());

	 * delete an image from a post


}
