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
     /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
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

    /**
     * @expectedException Agency\Exceptions\InvalidVideoException
     */
     public function test_validating_youtube_video_url()
    {
        // with protocol
        $this->assertTrue($this->validator->validate(['http://youtu.be/MMnvNtjPr-4']));
        $this->assertTrue($this->validator->validate(['http://www.youtube.com/embed/Abbs7Jmd-4']));
        $this->assertTrue($this->validator->validate(['https://www.youtube.com/watch?v=yVcePxjFujs']));

        // same as previous without protocol
        $this->assertTrue($this->validator->validate(['youtu.be/MMnvNtjPr-4']));
        $this->assertTrue($this->validator->validate(['www.youtube.com/embed/Abbs7Jmd-4']));
        $this->assertTrue($this->validator->validate(['www.youtube.com/watch?v=yVcePxjFujs']));

        // not youtube
        $this->assertFalse($this->validator->validate(['http://youtb.be/asdhflkjh']));
        $this->assertFalse($this->validator->validate(['https://www.youtube.net']));
        $this->assertFalse($this->validator->validate(['https://youtube.net']));
        $this->assertFalse($this->validator->validate(['https://video.com/lol']));
        $this->assertFalse($this->validator->validate(['']));
        $this->assertFalse($this->validator->validate([null]));
    }
}
