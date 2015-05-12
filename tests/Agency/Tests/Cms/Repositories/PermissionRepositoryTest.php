<?php namespace Agency\Tests\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;

use AblaFahita\Cms\Auth\Repositories\PermissionRepository;

class PermissionRepositoryTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->privileges = M::mock('AblaFahita\Cms\Auth\Repositories\PrivilegeRepository');
        $this->mPermission = M::mock('AblaFahita\Cms\Auth\Authorization\Entities\Permission');

        $this->permissions = new PermissionRepository($this->privileges, $this->mPermission);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    public function test_fetching_permissions_for_admin_over_resource()
    {
        $admin = M::mock('AblaFahita\Cms\Admin');
        $section = M::mock('AblaFahita\Cms\Section');

        $role = M::mock('AblaFahita\Cms\Auth\Authorization\Entities\Role');
        $role->shouldReceive('getAttribute')->once()
            ->with('permissions')
            ->andReturn(M::mock('AblaFahita\Cms\Auth\Authorization\PermissionsCollection'));

        $privilege = M::mock('AblaFahita\Cms\Auth\Authorization\Entities\Privilege');
        $privilege->shouldReceive('getAttribute')->once()
            ->with('role')
            ->andReturn($role);

        $this->privileges->shouldReceive('of')->once()
            ->with($admin, $section, false)
            ->andReturn($privilege);

        $permissions = $this->permissions->of($admin, $section);
        $this->assertInstanceOf('AblaFahita\Cms\Auth\Authorization\PermissionsCollection', $permissions);
    }

    /**
     * @depends test_fetching_permissions_for_admin_over_resource
     */
    public function test_returns_array_when_no_privileges_found()
    {
        $admin = M::mock('AblaFahita\Cms\Admin');
        $section = M::mock('AblaFahita\Cms\Section');

        $this->privileges->shouldReceive('of')->once();
        $this->assertEquals([], $this->permissions->of($admin, $section));
    }
}
