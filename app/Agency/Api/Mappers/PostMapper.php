<?php namespace Agency\Api\Mappers;

use Agency\Api\PostsCollection;

use Agency\Cms\Post;

class PostMapper{

	protected $post;

	protected $posts_collection;

	public function __construct()
	{
		$this->imageMapper = new ImageMapper();
		$this->videoMapper = new VideoMapper();
		$this->tagMapper = new TagMapper();

	}

	public function make($posts)
	{
		if (get_class($posts)=="Illuminate\Support\Collection"
			or get_class($posts)=="Illuminate\Database\Eloquent\Collection"
			or get_class($posts)== "Illuminate\Pagination\Paginator")
		{
			$this->posts_collection = new PostsCollection();

			foreach ($posts as $post) {
				$this->posts_collection->push($this->parseAndFill($post));
			}

			 return $this->posts_collection;
		} elseif (get_class($posts)=="Agency\Cms\Post") {

			return $this->parseAndFill($posts);
		}
	}

	public function parseAndFill($post)
	{
		$this->post['id'] = $post->id;
		$this->post['title'] = $post->title;
		$this->post['slug'] = $post->slug;
		$this->post['images'] = $this->imageMapper->make($post->getAllImages())->toArray();
		$this->post['videos'] = $this->videoMapper->make($post->getAllVideos())->toArray();
		$this->post['tags'] = $this->tagMapper->Make($post->tags()->get())->toArray();
		return $this->post;
	}


}