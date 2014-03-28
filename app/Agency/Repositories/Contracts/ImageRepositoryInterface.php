<?php namespace Agency\Repositories\Contracts;

use Agency\Media\Photos\Photo;

interface ImageRepositoryInterface {

	/**
	 * create a new image
	 *
	 * @param {Agency\Media\Photos\Photo} $original
	 * @param {Agency\Media\Photos\Photo} $thumbnail
	 * @param {Agency\Media\Photos\Photo} $small
	 * @param {Agency\Media\Photos\Photo} $square
	 *
	 * @return Illuminate\Database\Eloquent\Model of the original image
	 */
	public function create( Photo $original,
							Photo $thumbnail,
							Photo $small,
							Photo $square);

	public function getThumbnail($guid);

	public function getByGuid($guid);

    public function remove($images_id);
    /**
     * it takes an array of attributes and return an array of their model
     * @param  array $response 
     * @return array  Agency\Image
     */
    public function prepareToStore($response);	




}
