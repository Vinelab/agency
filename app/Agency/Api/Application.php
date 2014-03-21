<?php namespace Agency\Api;

use Eloquent;

class Application extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = "applications";

	protected $fillable = ["name","key","secret"];

	public function codes()
	{
		return $this->hasMany('Agency\Api\Code');
	}

}