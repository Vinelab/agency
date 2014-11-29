<?php namespace Agency\Contracts\Api;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

interface ApiInterface {

	public function respond($results,$total,$page);
}
