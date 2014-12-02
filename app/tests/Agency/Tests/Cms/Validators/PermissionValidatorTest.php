<?php namespace Agency\Tests\Cms\Validators;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Agency\Cms\Authority\Entities\Permission;

use DB, Artisan, TestCase, Mockery as M;

use Agency\Office\Validators\PermissionValidator;


class PermissionValidatorTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
		$this->validator = new PermissionValidator($this->mValidatorFactory);
	}

	public function test_permission_validator_provider_binding()
	{
		$validator = $this->app->make('Agency\Contracts\Office\Validators\PermissionValidatorInterface');
		$this->assertInstanceOf('Agency\Office\Validators\PermissionValidator', $validator);

	}

	public function test_passing_validation()
	{
		$this->assertTrue($this->validator->validate([
			'title' => 'some title',
			'alias' => 'some-title',
			'description' => 'some description here'
		]));
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_missing_title()
	{
		$this->validator->validate([
			'alias' => 'some-title',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_null_title()
	{
		$this->validator->validate([
			'title' => null,
			'alias' => 'some-title',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_empty_title()
	{
		$this->validator->validate([
			'title' => '',
			'alias' => 'some-title',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_long_title()
	{
		$this->validator->validate([
        	'title' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd',
			'alias' => 'some-title',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_long_alias()
	{
		$this->validator->validate([
			'title' => 'some_title',
        	'alias' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_space_in_alias()
	{
		$this->validator->validate([
			'title' => 'some title',
			'alias' => 'some title',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_upercase_in_alias()
	{
		$this->validator->validate([
			'title' => 'some title',
			'alias' => 'SOMETITLE',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_upercase_and_space_in_alias()
	{
		$this->validator->validate([
			'title' => 'some title',
			'alias' => 'Some Title',
			'description' => 'description here'
		]);
	}

	/**
	 * @depends test_passing_validation
	 * @expectedException Agency\Office\Exceptions\InvalidPermissionException
	 */
	public function test_fails_with_long_description()
	{
		$this->validator->validate([
			'title' => 'some_title',
			'alias' => 'some_title',
			'description' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsdbasdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsdbasdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsdbasdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd'
		]);
	}










}