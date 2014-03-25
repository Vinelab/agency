<?php namespace Agency\Tests\Repositories;

use TestCase, Mockery as M;
use Agency\Repositories\TagRepository;

class TagRepositoryTest extends TestCase {

    public function __construct()
    {
        $this->mock = M::mock('Eloquent');
    }

    public function setUp()
    {
        parent::setUp();

        $this->mTag = M::mock('Agency\Tag');
        $this->tags = new TagRepository($this->mTag);
    }

    public function test_tag_provider_bindings()
    {
        $tags = $this->app->make('Agency\Repositories\Contracts\TagRepositoryInterface');
        $this->assertInstanceOf('Agency\Repositories\TagRepository', $tags);
    }

    public function test_creating_tag()
    {
        $text = $slug = 'my-tag';

        $this->mTag->shouldReceive('firstOrCreate')->once()
            ->with(compact('text', 'slug'))->andReturn($this->mTag);
        $tag = $this->tags->create($text);

        $this->assertInstanceOf('Agency\Tag', $tag);
    }
}
