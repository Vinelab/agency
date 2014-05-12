<?php namespace Agency\Tests\Cms\Validators;

/**
 * 	@author  Ibrahim Fleifel <bob.fleifel@gmail.com>
 */


use Agency\Cms\Authority\Entities\Role;

use DB, Artisan, TestCase, Mockery as M;

use Agency\Cms\Validators\RoleValidator;

class RoleValidatorTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		Artisan::call('migrate');

		$this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
		$this->validator = new RoleValidator($this->mValidatorFactory);
	}

	public function test_role_validator_provider_binding()
	{
		$validator = $this->app->make('Agency\Cms\Validators\Contracts\RoleValidatorInterface');
		$this->assertInstanceOf('Agency\Cms\Validators\RoleValidator', $validator);
	}

	public function test_passing_validation()
	{
		$this->assertTrue($this->validator->validate([
			'title' => 'title here',
			'alias' => 'title-here'
		]));
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_fails_with_missing_title()
	{
		$this->validator->validate([
			'alias' => 'title-here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_fails_with_empty_title()
	{
		$this->validator->validate([
			'title' => '',
			'alias' => 'title-here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_fails_with_null_title()
	{
		$this->validator->validate([
			'title' => null,
			'alias' => 'title-here'
		]);
	}


	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_fails_with_long_title()
	{
		$this->validator->validate([
        	'title' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd',
			'alias' => 'title-here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_with_missing_alias()
	{
		$this->validator->validate([
			'title' => 'some-title'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_with_null_alias()
	{
		$this->validator->validate([
			'title' => 'some-title',
			'alias' => null
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_with_empty_alias()
	{
		$this->validator->validate([
			'title' => 'some-title',
			'alias' => ''
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Cms\Exceptions\InvalidRoleException
	 */
	public function test_fails_with_long_alias()
	{
		$this->validator->validate([
			'title' => 'some-title',
        	'alias' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd'
		]);
	}

}

