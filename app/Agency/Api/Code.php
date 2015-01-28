<?php namespace Agency\Api;
use NeoEloquent;

use Agency\Contracts\CodeInterface;

class Code extends NeoEloquent implements CodeInterface{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $label = 'Code';

	protected $fillable = ['application_id','code','valid'];

	public function application()
	{
		return $this->belongsTo('Agency\Api\Application');
	}


}
