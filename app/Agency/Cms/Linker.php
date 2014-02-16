<?php  namespace Agency\Cms; 

class Linker extends \Eloquent  {

	protected $table = "linkers";
	protected $fillable=["post_id","linkable_id","linkable_type"];

	
	public function linkable()
	{
		return $this->morphTo();
	}
}