<?php  namespace Agency\Cms; 


class Content extends \Eloquent {

	protected $table = "contents";
	protected $fillable = ["title","alias","parent_id"];
    protected $softDelete = true;


    public function getIdentifier()
    {
    	return $this->id;
    }

    public function getInstance()
    {
    	return $this;
    }
}