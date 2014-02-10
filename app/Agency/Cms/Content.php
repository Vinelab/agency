<?php  namespace Agency\Cms; 

use Agency\Cms\Repositories\Contracts\LinkableInterface;

class Content extends \Eloquent implements LinkableInterface  {

	protected $table = "contents";
	protected $fillable = ["title","alias","parent_id"];
    protected $softDelete = true;

	public function linker()
    {
        return $this->morphMany("Agency\Cms\Linker", "linkable");
    }

    public function getIdentifier()
    {
    	return $this->id;
    }

    public function getInstance()
    {
    	return $this;
    }
}