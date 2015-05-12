<?php namespace Agency\Api;

use NeoEloquent;

use Agency\Contracts\ApplicationInterface;

class Application extends NeoEloquent implements ApplicationInterface{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $label = 'Application';

	protected $fillable = ['name','key','secret'];

	public function codes()
	{
		return $this->hasMany('Agency\Api\Code');
	}

}
