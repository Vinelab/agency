<?php namespace Agency\Tests\Media\Videos;

use TestCase, Mockery as M;
use Agency\Media\Videos\Parser;
use Agency\Validators\Contracts\VideoValidatorInterface;

class ParserTest extends TestCase {

    public function setUp()
    {
        parent::setUp();
        $this->mVideo_validator = M::mock('Agency\Validators\Contracts\VideoValidatorInterface');
        $this->parser = new parser($this->mVideo_validator);
        $this->mVideo = M::mock('Agency\Video');

    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    public function test_make()
    {
        $this->mVideo->url = "https://www.youtube.com/watch?v=n4aXtZVaKHA";
        $this->mVideo->title = "title";
        $this->mVideo->desc = "desc";
        $this->mVideo->thumbnail = "thumbnail";

        $videos = [$this->mVideo, $this->mVideo, $this->mVideo];
        $this->mVideo_validator->shouldReceive('validate')->with(['url' => $this->mVideo->url])->andReturn(true);

        $result = $this->parser->make($videos);

        $this->assertInstanceOf('Agency\Video',$result[0]);

    }


}
