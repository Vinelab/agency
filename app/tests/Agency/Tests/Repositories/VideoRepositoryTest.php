<?php namespace Agency\Tests\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;
use Agency\Repositories\VideoRepository;

class VideoRepositoryTest extends TestCase {

    public function __construct()
    {
        $this->mock = M::mock('Eloquent');
    }

    public function setUp()
    {
        parent::setUp();

        $this->mVideo = M::mock('Agency\Video');
        $this->videos = new VideoRepository($this->mVideo);
    }

    public function test_videos_provider_bindnig()
    {
        $videos = $this->app->make('Agency\Repositories\Contracts\VideoRepositoryInterface');
        $this->assertInstanceOf('Agency\Repositories\VideoRepository', $videos);
    }

    public function test_creating_video()
    {
        $title = "Somebody's gonna get a hurt real baaad";
        $url = 'https://www.youtube.com/watch?v=yVcePxjFujs';
        $description = 'Russaaaaaaaal petteeeeeeeerssss :D';
        $thumbnail = 'http://i1.ytimg.com/vi/yVcePxjFujs/mqdefault.jpg';

        $this->mVideo->shouldReceive('create')->once()
            ->with(compact('title', 'url', 'description', 'thumbnail'))
            ->andReturn($this->mVideo);

        $video = $this->videos->create($title, $url, $description, $thumbnail);
        $this->assertInstanceOf('Agency\Video', $video);
    }

    public function test_extracting_youtube_video_id()
    {
        $id = $this->videos->extractYoutubeId('https://www.youtube.com/watch?v=yVcePxjFujs');
        $this->assertEquals('yVcePxjFujs', $id);

        $id = $this->videos->extractYoutubeId('http://youtu.be/MMnvNtjPr-4');
        $this->assertEquals('MMnvNtjPr-4', $id);

        $id = $this->videos->extractYoutubeId('//www.youtube.com/embed/Abbs7Jmd-4');
        $this->assertEquals('Abbs7Jmd-4', $id);

        $this->assertNull($this->videos->extractYoutubeId('http://not.youtube.url'));
        $this->assertNull($this->videos->extractYoutubeId('http://other.domain.com/watch?v=something'));
        $this->assertNull($this->videos->extractYoutubeId('http://youtb.be/something'));
        $this->assertNull($this->videos->extractYoutubeId('http://youtb.be/embed/something'));
        $this->assertNull($this->videos->extractYoutubeId(''));
        $this->assertNull($this->videos->extractYoutubeId(null));
    }

    public function test_validating_youtube_video_url()
    {
        // with protocol
        $this->assertTrue($this->videos->validateYoutubeUrl('http://youtu.be/MMnvNtjPr-4'));
        $this->assertTrue($this->videos->validateYoutubeUrl('http://www.youtube.com/embed/Abbs7Jmd-4'));
        $this->assertTrue($this->videos->validateYoutubeUrl('https://www.youtube.com/watch?v=yVcePxjFujs'));

        // same as previous without protocol
        $this->assertTrue($this->videos->validateYoutubeUrl('youtu.be/MMnvNtjPr-4'));
        $this->assertTrue($this->videos->validateYoutubeUrl('www.youtube.com/embed/Abbs7Jmd-4'));
        $this->assertTrue($this->videos->validateYoutubeUrl('www.youtube.com/watch?v=yVcePxjFujs'));

        // not youtube
        $this->assertFalse($this->videos->validateYoutubeUrl('http://youtb.be/asdhflkjh'));
        $this->assertFalse($this->videos->validateYoutubeUrl('https://www.youtube.net'));
        $this->assertFalse($this->videos->validateYoutubeUrl('https://youtube.net'));
        $this->assertFalse($this->videos->validateYoutubeUrl('https://video.com/lol'));
        $this->assertFalse($this->videos->validateYoutubeUrl(''));
        $this->assertFalse($this->videos->validateYoutubeUrl(null));
    }
}
