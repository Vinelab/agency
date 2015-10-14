<?php namespace Agency\Tests\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;
use Agency\Tag;
use Agency\Repositories\TagRepository;
use Agency\Contracts\HelperInterface;

class TagRepositoryTest extends TestCase {

    public function __construct()
    {
        $this->mock = M::mock('NeoEloquent');
    }

    public function setUp()
    {
        parent::setUp();

        $this->mTag = M::mock('Agency\Tag');
        $this->mHelper = M::mock('Agency\Contracts\HelperInterface');
        $this->tags = new TagRepository($this->mTag,$this->mHelper);
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function test_tag_provider_bindings()
    {
        $tags = $this->app->make('Agency\Contracts\Repositories\TagRepositoryInterface');
        $this->assertInstanceOf('Agency\Repositories\TagRepository', $tags);
    }

    public function test_creating_tag()
    {
        $text = $slug = 'my-tag';

        $this->mTag->shouldReceive('whereRaw')->andReturn($this->mTag);
        $this->mTag->shouldReceive('count')->andReturn(0);
        $this->mTag->shouldReceive('firstOrCreate')
            ->with(M::type('array'))->andReturn($this->mTag);

        $this->mHelper->shouldReceive('slugify')->with($text,$this->mTag)->andReturn($slug);

        $tag = $this->tags->create($text);

        $this->assertInstanceOf('Agency\Tag', $tag);
    }

    public function test_creating_multiple_tags()
    {
        $tags = ['my tag', 'another tag', 'some tag here'];
        $slugs = ['my-tag', 'another-tag', 'some-tag-here'];
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $coll->shouldReceive('lists')->with('id')->andReturn($coll)
            ->shouldReceive('lists')->with('slug')->andReturn(['some-slug'])
            ->shouldReceive('merge')->andReturn($coll)
            ->shouldReceive('offsetExists')->andReturn($coll);

        $this->mTag
            ->shouldReceive('whereRaw')->andReturn($this->mTag)
            ->shouldReceive('count')->andReturn(0)
            ->shouldReceive('whereIn')->with('slug',$slugs)->andReturn($this->mTag)
            ->shouldReceive('get')->andReturn($coll)
            ->shouldReceive('lists')->with('id')->andReturn([])
            ->shouldReceive('lists')->with('slug')->andReturn([])
            ->shouldReceive('create')->andReturn($this->mTag);

        $this->mHelper->shouldReceive('slugify')->with('my tag')->andReturn('my-tag');
        $this->mHelper->shouldReceive('slugify')->with('another tag')->andReturn('another-tag');
        $this->mHelper->shouldReceive('slugify')->with('some tag here')->andReturn('some-tag-here');


        $saved = $this->tags->splitFound($tags);


        $this->assertArrayHasKey('new', $saved);
        $this->assertArrayHasKey('existing', $saved);

    }
}
