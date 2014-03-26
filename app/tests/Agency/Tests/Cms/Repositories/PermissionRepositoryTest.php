<?php namespace Agency\Tests\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;
use Agency\Cms\Repositories\PermissionRepository;

class PermissionRepositoryTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mPermission = M::mock('Agency\Cms\Authority\Entities\Permission');
        $this->permissions = new PermissionRepository($this->mPermission);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    public function test_creating()
    {
        $this->mPermission->shouldReceive('create')->once()
            ->with(['title' => 'title', 'alias' => 'alias', 'description' => 'description'])
            ->andReturn($this->mPermission);

        $permission = $this->permissions->create('title', 'alias', 'description');
        $this->assertInstanceOf('Agency\Cms\Authority\Entities\Permission', $permission);
    }

    public function test_updating()
    {
        $id = 'perm-id';
        $title = 'Some Title';
        $alias = 'some-alias';
        $description = 'Description here';

        $this->mPermission->shouldReceive('findOrFail')->once()
            ->with($id)->andReturn($this->mPermission);

        $this->mPermission->shouldReceive('fill')->once()
            ->with(compact('title', 'alias', 'description'))->andReturn($this->mPermission);

        $this->mPermission->shouldReceive('save')->once()->andReturn($this->mPermission);

        $this->assertInstanceOf(
            'Agency\Cms\Authority\Entities\Permission',
            $this->permissions->update($id, $title, $alias, $description));
    }

}
