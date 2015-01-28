<?php namespace Agency\Tests\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;
use Agency\Cms\Repositories\RoleRepository;

class RoleRepositoryTest extends TestCase {

    public function __construct()
    {
        $this->mock = M::mock('NeoEloquent');
    }

    public function setUp()
    {
        parent::setUp();
        $this->mRole = M::mock('Agency\Cms\Authority\Entities\Role');
        $this->roles = new RoleRepository($this->mRole);
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function test_creating_roles()
    {
        $title = 'some title';
        $alias = 'some-alias';

        $this->mRole->shouldReceive('create')->once()
            ->with(compact('title','alias'))->andReturn($this->mRole);

        $role = $this->roles->create($title, $alias);

        $this->assertInstanceOf('Agency\Cms\Authority\Entities\Role', $role);
    }

    public function test_updating_role_permissions()
    {
        $id    = 'some-id';
        $ids = '1,2,3,4,5';
        $this->mRole->shouldReceive('findOrFail')->with($id)->andReturn($this->mRole);

        $this->mRole->shouldReceive('permissions')->once()->andReturn($this->mRole);

        $this->mRole->shouldreceive('sync')->once()
            ->with(explode(',', $ids))->andReturn($this->mRole);

        $role = $this->roles->updatePermissions($id, $ids);

        $this->assertInstanceOf('Agency\Cms\Authority\Entities\Role', $role);
    }

    public function test_fetching_with_permissions()
    {
        $this->mRole->shouldReceive('with')->once()->with('permissions')->andReturn($this->mRole);
        $this->mRole->shouldReceive('get')->once()->andReturn($this->mRole);

        $this->assertNotNull($this->roles->allWithPermissions());
    }
}
