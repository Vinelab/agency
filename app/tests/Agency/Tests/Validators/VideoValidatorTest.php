<?php namespace Agency\Tests\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase;
use Agency\Validators\VideoValidator;

class VideoValidatorTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
        $this->validator = new VideoValidator($this->mValidatorFactory);
    }

    public function test_video_validator_binding()
    {
        $validator = $this->app->make('Agency\Validators\Contracts\VideoValidatorInterface');
        $this->assertInstanceOf('Agency\Validators\VideoValidator', $validator);
    }

    public function test_passing_video_validation()
    {
        $this->assertTrue($this->validator->validate(['url' => 'http://some.url.here']));
    }

    /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
    public function test_fails_with_missing_url()
    {
        $this->validator->validate([]);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
    public function test_fails_with_null_url()
    {
        $this->validator->validate(['url' => null]);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
    public function test_fails_with_empty_url()
    {
        $this->validator->validate(['url' => '']);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
    public function test_fails_with_malformatted_url()
    {
        $this->validator->validate(['url' => 'this is not a url']);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
    public function test_fails_with_retarted_url()
    {
        $this->validator->validate(['url' => 'thisisnotaurl']);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
    public function test_fails_with_looooooooong_url()
    {
        $this->validator->validate(['url' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd']);
    }
}
