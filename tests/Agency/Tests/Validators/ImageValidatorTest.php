<?php namespace Agency\Tests\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;
use Agency\Validators\ImageValidator;

class ImageValidatorTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
        $this->validator = new ImageValidator($this->mValidatorFactory);
    }

    public function test_image_provider_binding()
    {
        $validator = $this->app->make('Agency\Contracts\Validators\ImageValidatorInterface');
        $this->assertInstanceOf('Agency\Validators\ImageValidator', $validator);
    }

    public function test_passing_validation()
    {
        $this->assertTrue($this->validator->validate(['url'=>'http://some.url.com']));
    }

    /**
     * @expectedException Agency\Exceptions\InvalidImageException
     */
    public function test_fails_with_missing_url()
    {
        $this->validator->validate([]);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidImageException
     */
    public function test_fails_with_null_url()
    {
        $this->validator->validate(['url' => null]);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidImageException
     */
    public function test_fails_with_empty_url()
    {
        $this->validator->validate(['url' => '']);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidImageException
     */
    public function test_fails_with_malformatted_url()
    {
        $this->validator->validate(['url' => 'this is not a url']);
    }

    /**
     * @expectedException Agency\Exceptions\InvalidImageException
     */
    public function test_fails_with_retarted_url()
    {
        $this->validator->validate(['url' => 'thisisnotaurl']);
    }
}
