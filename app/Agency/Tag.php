<?php  namespace Agency;

use NeoEloquent;

class Tag extends NeoEloquent  {

	protected $label = 'Tag';

	protected $fillable = ['text','slug'];

	public function posts()
    {
        return $this->belongsToMany("Agency\Post", "TAG");
    }

	public function dbTable()
	{
		return $this->table;
	}

}
