<?php namespace Agency\Tests\Cms\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Artisan, TestCase, Mockery as M;
use Agency\Cms\Validators\AdminValidator;

class AdminValidatorTest extends TestCase {

    // public function __construct()
    // {
    //     $this->mock = M::mock('Eloquent');
    // }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        $this->seed();

        $this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
        $this->validator = new AdminValidator($this->mValidatorFactory);
    }

    public function test_admin_validator_provider_binding()
    {
        $validator = $this->app->make('Agency\Cms\Validators\Contracts\AdminValidatorInterface');
        $this->assertInstanceOf('Agency\Cms\Validators\AdminValidator', $validator);
    }

    public function test_passing_validation()
    {
        $this->assertTrue($this->validator->validate(['name' => 'Admin Name', 'email' => 'not-existing@mail.net']));
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     */
    public function test_fails_with_missing_name()
    {
        $this->validator->validate(['email'=>'some@mail.net']);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     */
    public function test_fails_with_null_name()
    {
        $this->validator->validate(['name' => null, 'email' => 'another@email.com']);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     */
    public function test_fails_with_empty_name()
    {
        $this->validator->validate(['name' => '', 'email' => 'another@email.com']);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     */
    public function test_fails_with_missing_email()
    {
        $this->validator->validate(['name' => 'Some Name']);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     */
    public function test_fails_with_null_email()
    {
        $this->validator->validate(['name' => 'Some Name', 'email' => null]);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     */
    public function test_fails_with_empty_email()
    {
        $this->validator->validate(['name' => 'Some Name', 'email' => '']);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     */
    public function test_fails_with_malformatted_email()
    {
        $this->validator->validate(['name' => 'Some Name', 'email' => 'notan@email.']);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Cms\Exceptions\InvalidAdminException
     *
     * NOTE: MAKE SURE TO HAVE THIS EMAIL SET IN THE SEEDS
     */
    public function test_fails_with_duplicate_email()
    {
        $this->validator->validate(['name' => 'Some Name', 'email' => 'bob.fleifel@gmail.com']);
    }

}
