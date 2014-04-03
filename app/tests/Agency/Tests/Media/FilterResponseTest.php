<?php namespace Agency\Tests\Media;

use TestCase, Mockery as M;
use Agency\Contracts\HelperInterface;
use Agency\Media\Photos\FilterResponse;

class FilterResponseTest extends TestCase {

    public function setUp()
    {
        parent::setUp();
        $this->mHelper = M::mock('Agency\Contracts\HelperInterface');
        $this->filter_response = new FilterResponse($this->mHelper);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    public function test_make()
    {
        $mPhoto = M::mock('Agency\Media\Photos\photo');
        $mPhoto->url = "url";

        $unique_id = $this->mHelper->shouldReceive('getUniqueId')->once()->andReturn("uniqueId");

        $image=[
            'original'=>$mPhoto,
            'thumbnail'=>$mPhoto,
            'square'=>$mPhoto,
            'small'=>$mPhoto
        ];

        $response=[$image];
        


        $result = $this->filter_response->make($response);

        $this->assertArrayHasKey('without_original',$result);
        $this->assertArrayHasKey('originals',$result);


    }


}
