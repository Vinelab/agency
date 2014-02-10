<?php namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\ContentsRepositoryInterface;

use Agency\Cms\Content;

class ContentRepository extends Repository implements ContentsRepositoryInterface {

	protected $content;


	public function __construct(Content $content)
	{
		$this->content = $this->model= $content;
	}

	public function create($title,$alias,$parent_id)
	{
		$content = $this->content->create(compact("title","alias","parent_id"));
		return $content;
	}

	public function update($id,$title)
	{
		$content=$this->content->find($id);
		if(!is_null($content))
		{
			$content->title=$title;
			$content->save();
			return $content;
		}
			return false;
	}

	function set($content)
	{
		$this->content = $content;
	}

	

	


}