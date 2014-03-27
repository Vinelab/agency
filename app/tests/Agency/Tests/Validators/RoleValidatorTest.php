<?php namespace Agency\Tests\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase;
use Agency\Cms\Validators\RoleValidator;

class RoleValidatorTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
        $this->validator = new RoleValidator($this->mValidatorFactory);
    }

    public function test_role_validator_binding()
    {
        $validator = $this->app->make('Agency\Cms\Validators\Contracts\RoleValidatorInterface');
        $this->assertInstanceOf('Agency\Cms\Validators\RoleValidator', $validator);
    }

    public function test_passing_role_validation()
    {
        $this->assertTrue($this->validator->validate(['title' => 'some title', 'alias'=>'some-alias']));
    }

    /**
     * @depends test_passing_role_validation
     * @expectedException Agency\Cms\Exceptions\InvalidRoleException
     */
    public function test_fails_with_missing_title()
    {
        $this->validator->validate(['alias' => 'alias-only']);
    }

    /**
     * @depends test_passing_role_validation
     * @expectedException Agency\Cms\Exceptions\InvalidRoleException
     */
    public function test_fails_with_null_title()
    {
        $this->validator->validate(['title' => null, 'alias' => 'alias-only']);
    }

    /**
     * @depends test_passing_role_validation
     * @expectedException Agency\Cms\Exceptions\InvalidRoleException
     */
    public function test_fails_with_empty_title()
    {
        $this->validator->validate(['title' => '', 'alias' => 'alias-only']);
    }

    /**
     * @depends test_passing_role_validation
     * @expectedException Agency\Cms\Exceptions\InvalidRoleException
     */
    public function test_fails_with_huge_title()
    {
        $this->validator->validate(['title' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd',
            'alias' => 'alias-only']);
    }

    /**
     * @depends test_passing_role_validation
     * @expectedException Agency\Cms\Exceptions\InvalidRoleException
     */
    public function test_fails_with_alias_in_space()
    {
        $this->validator->validate([
            'title' => 'some title',
            'alias' => 'something spaced'
        ]);
    }

    /**
     * @depends test_passing_role_validation
     * @expectedException Agency\Cms\Exceptions\InvalidRoleException
     */
    public function test_fails_with_big_ass_aliass()
    {
        $this->validator->validate([
            'title' => 'some title',
            'alias' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd'
        ]);
    }
}
