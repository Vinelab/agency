<?php namespace Agency\Api\Mappers;

use Agency\Api\PostsCollection;

use Agency\Cms\Post;

class PostMapper{

	protected $post;

	protected $postsCollection;

	public function __construct()
	{
		$this->imageMapper = new ImageMapper();
		$this->videoMapper = new VideoMapper();
		$this->tagMapper = new TagMapper();

	}

	public function make($posts)
	{
		$this->postsCollection = new PostsCollection();
		foreach ($posts as $post) {
			$this->postsCollection->push($this->parseAndFill($post));
		}

		return $this->postsCollection;
	}

	public function parseAndFill(Post $post)
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