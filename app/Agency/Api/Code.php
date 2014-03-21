<?php namespace Agency\Api;

class Code extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = "codes";

	protected $fillable = ["application_id","code","valid"];

	public function application()
	{
		return $this->belongsTo('Agency\Api\Application');
	}
	

}