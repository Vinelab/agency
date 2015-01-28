<?php namespace Agency\Tests\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;

use Agency\Cms\Repositories\AdminRepository;

class AdminRepositoryTest extends TestCase {

    

    public function setUp()
    {
        parent::setUp();

        $this->mAdmin = M::mock('Agency\Contracts\Cms\AdminInterface');
        $this->admin = $this->app->make('Agency\Cms\Admin');

        $this->admins = new AdminRepository($this->admin);
    }

    public function tearDown()
    {

        M::close();
        parent::tearDown();

    }

    public function test_admin_provider_bindings()
    {
        $admin_repo = $this->app->make('Agency\Contracts\Cms\Repositories\AdminRepositoryInterface');

        $this->assertInstanceOf('Agency\Cms\Repositories\AdminRepository', $admin_repo);

        $admin = $this->app->make('Agency\Contracts\Cms\AdminInterface');

        $this->assertInstanceOf('Agency\Cms\Admin', $admin);
    }

    public function test_creating_admin()
    {
        $name = 'Battouta';
        $email = 'battouta@disney.ass';

        $this->mAdmin->shouldReceive('create')
            ->with(M::subset(['name' => $name, 'email' => $email]))->andReturn($this->mAdmin);

        $admin = $this->admins->create($name, $email);

        $this->assertInstanceOf('Agency\Cms\Admin', $admin);
        $this->assertObjectNotHasAttribute('password', $admin);
        $this->assertNotNull($admin->raw_password);
    }

    public function test_updating_admin()
    {

        $admin = $this->createAdmin();

        $id = $admin->id;
        $name = $admin->name;
        $email =$admin->email;

        $this->mAdmin->shouldReceive('findOrFail')->with($id)->andReturn($this->mAdmin);

        $this->mAdmin->shouldReceive('fill')
            ->with([
                'name' => $name,
                'email' => $email
            ])->andReturn($this->mAdmin);

        $this->mAdmin->shouldReceive('save');

        $this->admins->update($id, $name, $email);
    }

    // public function test_generates_random_password_quickly()
    // {
    //     $generated = [];

    //     for($i = 0; $i < 100; $i++)
    //     {
    //         $pass = $this->admins->generatePassword();
    //         //returning null value 

    //         $this->assertNotContains($pass, $generated);
    //         array_push($generated, $pass);
    //     }
    // }

    public function createAdmin()
    {
        $name = 'Battouta';
        $email = 'battouta@disney.ass';

        return $this->admins->create($name, $email);
    }

}
