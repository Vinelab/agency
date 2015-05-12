<?php namespace Agency\Tests\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

 use TestCase, Mockery as M;
 use AblaFahita\Cms\Repositories\SectionRepository;

 class SectionRepositoryTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mSection = M::mock('AblaFahita\Cms\Section');
        $this->sections = new SectionRepository($this->mSection);
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function test_bindings()
    {
        $sections = $this->app->make('AblaFahita\Contracts\Cms\Repositories\SectionRepositoryInterface');
        $this->assertInstanceOf('AblaFahita\Cms\Repositories\SectionRepository', $sections);
    }

    public function test_creating_section()
    {
        $title = 'sec title';
        $alias = 'my-alias';
        $icon = 'some-icon';
        $is_fertile = true;
        $is_roleable = true;

        $this->mSection->shouldReceive('create')
            ->with(compact('title', 'alias', 'icon', 'is_fertile', 'is_roleable'))
            ->andReturn($this->mSection);

        $section = $this->sections->create($title, $alias, $icon, $is_fertile, $is_roleable);
        $this->assertInstanceOf('AblaFahita\Cms\Section', $section);
    }

    public function test_updating_section()
    {
        $id    = 'sec-id';
        $title = 'updated sec title';
        $alias = 'updated-alias';
        $icon  = 'updated-icon';
        $is_fertile  = false;
        $is_roleable = false;

        $this->mSection->shouldReceive('findOrFail')->with($id)->andReturn($this->mSection);

        $this->mSection->shouldReceive('fill')
            ->with(compact('title', 'alias', 'icon', 'is_fertile', 'is_roleable'))
            ->andReturn($this->mSection);

        $this->mSection->shouldReceive('save');

        $section = $this->sections->update($id, $title, $alias, $icon, $is_fertile, $is_roleable);
        $this->assertInstanceOf('AblaFahita\Cms\Section', $section);
    }

    public function test_fetching_roleable_sections()
    {
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');
        $this->mSection->shouldReceive('where')->with('is_roleable', true)
            ->andReturn($this->mSection);
        $this->mSection->shouldReceive('get')->andReturn($coll);

        $sections = $this->sections->roleable();
        $this->assertEquals($coll, $sections);
    }

    public function test_fetching_infertile_sections()
    {
        $alias = 'my-alias';
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mSection->shouldReceive('where')->with('alias', $alias)
            ->andReturn($this->mSection);
        $this->mSection->shouldReceive('first');
        $this->mSection->shouldReceive('where')->with('is_fertile', false)
            ->andReturn($this->mSection)
            ->shouldReceive('where')->with('parent_id','<>',0)->andReturn($this->mSection);
        $this->mSection->shouldReceive('get')->andReturn($coll);

        $sections = $this->sections->infertile($alias);
        $this->assertEquals($coll, $sections);
    }

 }
