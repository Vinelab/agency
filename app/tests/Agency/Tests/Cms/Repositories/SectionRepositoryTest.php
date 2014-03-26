<?php namespace Agency\Tests\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

 use TestCase, Mockery as M;
 use Agency\Repositories\SectionRepository;

 class SectionRepositoryTest extends TestCase {

    public function __construct()
    {
        $this->mock = M::mock('eloquent');
    }

    public function setUp()
    {
        parent::setUp();

        $this->mSection = M::mock('Agency\Section');
        $this->sections = new SectionRepository($this->mSection);
    }

    public function test_bindings()
    {
        $sections = $this->app->make('Agency\Repositories\Contracts\SectionRepositoryInterface');
        $this->assertInstanceOf('Agency\Repositories\SectionRepository', $sections);
    }

    public function test_creating_section()
    {
        $title = 'sec title';
        $alias = 'my-alias';
        $icon = 'some-icon';
        $parent_id = 0;
        $is_fertile = true;
        $is_roleable = true;

        $this->mSection->shouldReceive('create')->once()
            ->with(compact('title', 'alias', 'icon', 'parent_id', 'is_fertile', 'is_roleable'))
            ->andReturn($this->mSection);

        $section = $this->sections->create($title, $alias, $icon, $parent_id, $is_fertile, $is_roleable);
        $this->assertInstanceOf('Agency\Section', $section);
    }

    public function test_updating_section()
    {
        $id    = 'sec-id';
        $title = 'updated sec title';
        $alias = 'updated-alias';
        $icon  = 'updated-icon';
        $parent_id   = 1;
        $is_fertile  = false;
        $is_roleable = false;

        $this->mSection->shouldReceive('findOrFail')->once()->with($id)->andReturn($this->mSection);

        $this->mSection->shouldReceive('fill')->once()
            ->with(compact('title', 'alias', 'icon', 'parent_id', 'is_fertile', 'is_roleable'))
            ->andReturn($this->mSection);

        $this->mSection->shouldReceive('save')->once();

        $section = $this->sections->update($id, $title, $alias, $icon, $parent_id, $is_fertile, $is_roleable);
        $this->assertInstanceOf('Agency\Section', $section);
    }

    public function test_fetching_roleable_sections()
    {
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');
        $this->mSection->shouldReceive('where')->once()->with('is_roleable', true)
            ->andReturn($this->mSection);
        $this->mSection->shouldReceive('get')->once()->andReturn($coll);

        $sections = $this->sections->roleable();
        $this->assertEquals($coll, $sections);
    }

    public function test_fetching_children_sections()
    {
        $alias = 'content';

        $this->mSection->shouldReceive('with')->once()->with('sections')->andReturn($this->mSection);
        $this->mSection->shouldReceive('where')->once()
            ->with('alias', $alias)
            ->andReturn($this->mSection);

        $this->mSection->shouldReceive('first')->once();

        $this->sections->children($alias);
    }

    public function test_fetching_infertile_sections()
    {
        $alias = 'my-alias';
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mSection->shouldReceive('where')->once()->with('alias', $alias)
            ->andReturn($this->mSection);
        $this->mSection->shouldReceive('first')->once();
        $this->mSection->shouldReceive('where')->once()->with('is_fertile', false)
            ->andReturn($this->mSection);
        $this->mSection->shouldReceive('get')->once()->andReturn($coll);

        $sections = $this->sections->infertile($alias);
        $this->assertEquals($coll, $sections);
    }

 }
