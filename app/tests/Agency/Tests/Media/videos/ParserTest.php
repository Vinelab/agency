<?php namespace Agency\Tests\Media\Videos;

use TestCase, Mockery as M;
use Agency\Media\Videos\Parser;

class FilterResponseTest extends TestCase {

    public function setUp()
    {
        parent::setUp();
        $this->parser = new parser();
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

        $result = $this->parser->make($videos);

        $this->assertInstanceOf('Agency\Video',$result[0]);

    }

    public function test_validating_youtube_video_url()
    {
        // with protocol
        $this->assertTrue($this->parser->validateYoutubeUrl('http://youtu.be/MMnvNtjPr-4'));
        $this->assertTrue($this->parser->validateYoutubeUrl('http://www.youtube.com/embed/Abbs7Jmd-4'));
        $this->assertTrue($this->parser->validateYoutubeUrl('https://www.youtube.com/watch?v=yVcePxjFujs'));

        // same as previous without protocol
        $this->assertTrue($this->parser->validateYoutubeUrl('youtu.be/MMnvNtjPr-4'));
        $this->assertTrue($this->parser->validateYoutubeUrl('www.youtube.com/embed/Abbs7Jmd-4'));
        $this->assertTrue($this->parser->validateYoutubeUrl('www.youtube.com/watch?v=yVcePxjFujs'));

        // not youtube
        $this->assertFalse($this->parser->validateYoutubeUrl('http://youtb.be/asdhflkjh'));
        $this->assertFalse($this->parser->validateYoutubeUrl('https://www.youtube.net'));
        $this->assertFalse($this->parser->validateYoutubeUrl('https://youtube.net'));
        $this->assertFalse($this->parser->validateYoutubeUrl('https://video.com/lol'));
        $this->assertFalse($this->parser->validateYoutubeUrl(''));
        $this->assertFalse($this->parser->validateYoutubeUrl(null));
    }


}
