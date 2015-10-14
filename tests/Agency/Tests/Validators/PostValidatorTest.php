<?php namespace Agency\Tests\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;
use Agency\Validators\PostValidator;

class PostValidatorTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
        $this->validator = new PostValidator($this->mValidatorFactory);
    }

    public function test_post_validator_binding()
    {
        $validator = $this->app->make('Agency\Contracts\Validators\PostValidatorInterface');
        $this->assertInstanceOf('Agency\Validators\PostValidator', $validator);
    }

    public function test_passes_post_validation()
    {
        $this->assertTrue($this->validator->validate(['title' => 'some post title here']));
    }

    /**
     * @depends test_passes_post_validation
     * @expectedException Agency\Exceptions\InvalidPostException
     */
    public function test_fails_with_missing_title()
    {
        $this->validator->validate(['slug' => 'something', 'other_stuff'=>'here']);
    }

    /**
     * @depends test_passes_post_validation
     * @expectedException Agency\Exceptions\InvalidPostException
     */
    public function test_fails_with_null_title()
    {
        $this->validator->validate(['slug' => 'something', 'title'=>null]);
    }

    /**
     * @depends test_passes_post_validation
     * @expectedException Agency\Exceptions\InvalidPostException
     */
    public function test_fails_with_empty_title()
    {
        $this->validator->validate(['slug' => 'something', 'title' => '']);
    }

    /**
     * @depends test_passes_post_validation
     * @expectedException Agency\Exceptions\InvalidPostException
     */
    public function test_fails_with_cocklong_title()
    {
        // 256 chars
        $this->validator->validate(['slug' => 'something',
            'title' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd']);
    }
}
