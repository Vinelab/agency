<?php  namespace Agency;

use Eloquent;

class Tag extends Eloquent  {

	protected $table = "tags";
	protected $fillable = ["text","slug"];

	public function posts()
    {
        return $this->belongsToMany("Agency\Post");
    }

}
