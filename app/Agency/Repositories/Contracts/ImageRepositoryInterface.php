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

    public function groupDelete($images_id);


}
