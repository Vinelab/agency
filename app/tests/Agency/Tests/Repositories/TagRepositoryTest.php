<?php namespace Agency\Tests\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;
use Agency\Tag;
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

        $this->mTag->shouldReceive('whereRaw')->once()->andReturn($this->mTag);
        $this->mTag->shouldReceive('count')->once()->andReturn(0);
        $this->mTag->shouldReceive('firstOrCreate')->once()
            ->with(compact('text', 'slug'))->andReturn($this->mTag);
        $tag = $this->tags->create($text);

        $this->assertInstanceOf('Agency\Tag', $tag);
    }

    public function test_creating_multiple_tags()
    {
        $tags = ['my tag', 'another tag', 'some tag here'];
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mTag
            ->shouldReceive('whereRaw')->times(count($tags))->andReturn($this->mTag)
            ->shouldReceive('count')->times(count($tags))->andReturn(0)
            ->shouldReceive('saveMany')->with(M::type('array'))
                ->times(count($tags))
                ->andReturn($this->mTag)
            ->shouldReceive('list')->once()->with('id')->andReturn($coll);

        $saved = $this->tags->createMany($tags);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $saved);
    }
}
