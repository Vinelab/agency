<?php  namespace Agency;

use Eloquent;

class Media extends Eloquent  {

	protected $table = 'media';

	protected $fillable = ['post_id','media_id','media_type'];

	public function posts()
	{
		return $this->belongsTo('Agency\Cms\Post');
	}

	public function media()
	{
		return $this->morphTo();
	}

}
