<?php namespace Agency\Cms\Repositories;

use Agency\Cms\Repositories\Contracts\ContentRepositoryInterface;

use Agency\Cms\Content;

class ContentRepository extends Repository implements ContentRepositoryInterface {

	protected $content;


	public function __construct(Content $content)
	{
		$this->content = $this->model  = $content;
	}

	public function create($title,$alias,$parent_id)
	{
		$content = $this->content->create(compact("title","alias","parent_id"));
		return $content;
	}

	public function update($id,$title,$parent_id)
	{	
		try {
				$content= $this->content->find($id);

				if($content)
				{
					$content->title = $title;
					$content->parent_id = $parent_id;
					return $content->save();
				}
			} catch(Exception $e){
				return Response::json($e->getMessage());
			}
	}

	function set($content)
	{
		$this->content = $this->model = $content;
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

	public function delete($id)
	{
		try {
            $this->model = $this->find($id);
            return $this->model->delete(); 
       	} catch (Exception $e) {
           return Response::json(['message'=>$e->getMessage()]);
       }
	}

	

	


}