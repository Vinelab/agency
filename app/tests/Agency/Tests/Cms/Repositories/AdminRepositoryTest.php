<?php namespace Agency\Tests\Cms\Repositories;

use TestCase, Mockery as M;

use Agency\Cms\Repositories\AdminRepository;

class AdminRepositoryTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mAdmin = M::mock('Agency\Cms\Contracts\AdminInterface');

        $this->admins = new AdminRepository($this->mAdmin);
    }

    public function tearDown()
    {
        parent::tearDown();

        // M::close();
    }

    public function test_creating_admin()
    {
        $name = 'Battouta';
        $email = 'battouta@disney.ass';

        $this->mAdmin->shouldReceive('create')->once()
            ->with(M::subset(['name' => $name, 'email' => $email]))->andReturn($this->mAdmin);

        $admin = $this->admins->create($name, $email);

        $this->assertInstanceOf('Agency\Cms\Contracts\AdminInterface', $admin);
        $this->assertObjectNotHasAttribute('password', $admin);
        $this->assertObjectHasAttribute('raw_password', $admin);
        $this->assertNotNull($admin->raw_password);
    }

    public function test_updating_admin()
    {
        $id = 'some-id';
        $name = 'Mickey';
        $email = 'muje@tuje.fije';

        $this->mAdmin->shouldReceive('findOrFail')->once()->with($id)->andReturn($this->mAdmin);

        $this->mAdmin->shouldReceive('fill')->once()
            ->with([
                'name' => $name,
                'email' => $email
            ])->andReturn($this->mAdmin);

        $this->mAdmin->shouldReceive('save')->once();

        $this->admins->update($id, $name, $email);
    }

    public function test_generates_random_password_quickly()
    {
        $generated = [];

        for($i = 0; $i < 100; $i++)
        {
            $pass = $this->admins->generatePassword();
            $this->assertNotContains($pass, $generated);
            array_push($generated, $pass);
        }
    }

    public function test_generates_random_reset_codes_quickly_and_saves_it()
    {
        $iterations = 100;
        $email = 'my@milkshake.biz';
        $generated = [];

        $this->mAdmin->shouldReceive('where')->with('email', $email)->times($iterations)->andReturn($this->mAdmin);
        $this->mAdmin->shouldReceive('first')->times($iterations)->andReturn($this->mAdmin);
        $this->mAdmin->shouldReceive('save')->times($iterations);

        for ($i = 0; $i < $iterations; $i++)
        {
            $admin = $this->admins->generateCode($email);
            $this->assertObjectHasAttribute('code', $admin);
            $this->assertNotContains($admin->code, $generated);
            array_push($generated, $admin->code);
        }
    }

    public function test_changing_password()
    {
        $id = 'admin-id';
        $pass = 'new-password';

        $this->mAdmin->shouldReceive('findOrFail')->with($id)->once()->andReturn($this->mAdmin);
        $this->mAdmin->shouldReceive('save')->once()->andReturn($this->mAdmin);

        $admin = $this->admins->changePassword($id, $pass);
        $this->assertInstanceOf('Agency\Cms\Contracts\AdminInterface', $admin);
    }

}
