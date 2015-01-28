<?php namespace Agency\Api\Mappers;

use Agency\Api\PostsCollection;

use Agency\Post;

class PostMapper{

	protected $post;

	protected $posts_collection;

	public function __construct()
	{
		$this->image_mapper = new ImageMapper();
		$this->video_mapper = new VideoMapper();
		$this->tag_mapper = new TagMapper();
		$this->section_mapper = new SectionMapper();

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

		} elseif (get_class($posts)=="Agency\Post") {

			return $this->parseAndFill($posts);
		}
	}

	public function parseAndFill($post)
	{
		$this->post['id'] = $post->id;
		$this->post['title'] = $post->title;
		$this->post['body'] = $post->body;
		$this->post['slug'] = $post->slug;
		$this->post['share_url'] = $post->shareUrl();
		$this->post['featured'] = ($post->featured == "true")? true : false;
		$this->post['publish_date'] = $post->publish_date;
		$this->post['cover'] = $this->image_mapper->parseAndFill($post->coverImage);
		$this->post['images'] = $this->image_mapper->make($post->images)->toArray();
		$this->post['videos'] = $this->video_mapper->make($post->videos)->toArray();
		$this->post['tags'] = $this->tag_mapper->make($post->tags)->toArray();

		$this->post['section'] = $this->section_mapper->parseAndFill($post->section);

		return $this->post;
	}


}
