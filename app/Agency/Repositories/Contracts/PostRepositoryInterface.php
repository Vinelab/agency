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
	public function update($id, $title, $slug, $body, $admin_id, $section_id, $publish_date, $publish_state);

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

	/**
	 * delete an image from a post
	 * according to the post id and
	 * the image id parameters
	 *
	 * @param integer $post_id
	 * @param integer $image_id
	 * @return boolean true if success 
	 * @return Exception in case of error
	 */
	public function detachImages($post_id, $image_ids);

	/**
	 * delete all videos from a post
	 * according to the post id
	 * @return boolean true if success 
	 * @return Exception in case of error
	 */
	public function detachVideos($post_id, $videos_ids);

	/**
	 * Add tags to post
	 * @param integer $id           	post id
	 * @param array $new_tags 			array of CMS\Tag
	 * @param array $existing_tags		array of integers representing the
	 *                              	the ids of the existing tags
	 */
	public function addTags($id,$new_tags,$existing_tags);

	/**
	 * Add images to post
	 * @param integer $id     post id
	 * @param array $images  array of images
	 */
	public function addImages($id,$images);


	public function detachTags($id);





}
