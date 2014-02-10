<?php namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\ContentsRepositoryInterface;

use Agency\Cms\Content;

class ContentRepository implements ContentsRepositoryInterface {

	protected $content;


	public function __construct(Content $content)
	{
		$this->content=$content;
	}
	
	public function rules()
	{
		return $this->rules;
	}

	public function create($title,$alias,$parent_id)
	{
		$content = $this->content->create(compact("title","alias","parent_id"));
		return $content;
	}

	public function all()
	{
		$contents=$this->content->all();
		return $contents;
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

	public function delete($id)
	{
		$content=$this->content->find($id);
		if(!is_null($content))
		{
			$content->delete();
			return true;
		}
		return false;
	}

	public function findBy($attribute,$value)
	{
		$content=$this->content->where($attribute,$value);
		if($content->count()!=0)
		{
			return $content;
		}
		return false;
	}

	function set($content)
	{
		$this->content = $content;
	}

	

	


}