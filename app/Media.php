<?php  namespace Agency;

use Eloquent;

class Media extends Eloquent  {

	protected $table = 'medias';

	protected $fillable = ['post_id','media_id','media_type'];

	public function posts()
	{
		return $this->morphedByMany('Agency\Post','media');
	}

	public function media()
	{
		return $this->morphTo();
	}

}
