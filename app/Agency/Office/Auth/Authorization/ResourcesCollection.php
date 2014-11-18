<?php namespace Agency\Office\Auth\Authorization;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\Collection;

class ResourcesCollection extends Collection {

	/**
	 * Get the ids of resources.
	 *
	 * @return array
	 */
	public function getIds()
	{
		return $this->lists('id');
	}
}
