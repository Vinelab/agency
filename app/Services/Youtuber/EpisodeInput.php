<?php namespace Agency\Services\Youtuber;

use Illuminate\Support\Facades\Input;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

class EpisodeInput
{

    /**
     * @var string
     */
    private $episode_title;

    /**
     * @var string
     */
    private $episode_slug_former;

    /**
     * @var string
     */
    private $live_channel;

    /**
     * @var array
     */
    private $live_videos;

    /**
     * @var string
     */
    private $behind_the_scenes_channel;

    /**
     * @var array
     */
    private $behind_the_scenes_videos;

    /**
     * @var string
     */
    private $publish_date;

    /**
     * @var string
     */
    private $publish_state;

    /**
     * @var int
     */
    private $section_id;

    /**
     * @var int
     */
    private $writer_id;

    /**
     * @var
     */
    private $behind_the_scenes_end;

    /**
     * @var
     */
    private $behind_the_scenes_title;

    /**
     * @var
     */
    private $live_end;

    /**
     * @var
     */
    private $live_title;

    /**
     * @param $input
     */
    public function __construct($input)
    {
        $this->fill($input);
    }

    /**
     * fill the inputs in this object
     *
     * @param $input
     */
    private function fill($input)
    {
        // merge the input data to insure nothing is missed
        $input = array_merge($this->expectedInput(), $input);

        $this->setEpisodeTitle($this->get($input['title']));
        $this->setEpisodeSlugFormer($this->get($input['slug_former']));
        $this->setLiveChannel($this->get($input['live-channel']));
        $this->setLiveVideos($this->get($input['live-video']));
        $this->setLiveEnd($this->get($input['live-end']));
        $this->setLiveTitle($this->get($input['live-title']));
        $this->setBehindTheSceneChannel($this->get($input['behind-channel']));
        $this->setBehindTheSceneVideos($this->get($input['behind-video']));
        $this->setBehindTheSceneEnd($this->get($input['behind-end']));
        $this->setBehindTheSceneTitle($this->get($input['behind-title']));
        $this->setPublishDate($this->get($input['publish_date']));
        $this->setPublishState($this->get($input['publishstate']));
        $this->setWriterId($this->get($input['writer']));
    }

    /**
     * return the expected input array to be merged
     *
     * @return array
     */
    private function expectedInput()
    {
        return [
            'title'          => '',
            'live-channel'   => '',
            'live-video'     => '',
            'live-end'       => '',
            'live-title'     => '',
            'behind-channel' => '',
            'behind-video'   => '',
            'behind-video'   => '',
            'behind-end'     => '',
            'behind-title'   => '',
            'publish_date'   => '',
            'publishstate'   => '',
            'writer'         => '',
        ];
    }

    /**
     * @param string $episode_title
     */
    private function setEpisodeTitle($episode_title)
    {
        $this->episode_title = $episode_title;
    }

    /**
     * @param string $episode_slug_former
     */
    private function setEpisodeSlugFormer($episode_slug_former)
    {
        $this->episode_slug_former = $episode_slug_former;
    }

    /**
     * return input if not empty string or null otherwise
     *
     * @param $variable string
     *
     * @return null or string
     */
    private function get($variable)
    {
        if (empty($variable)) {
            return null;
        }

        return $variable;
    }

    /**
     * @param string $live_channel
     */
    private function setLiveChannel($live_channel)
    {
        $this->live_channel = $live_channel;
    }

    /**
     * @param array $live_videos
     */
    private function setLiveVideos($live_videos)
    {
        $this->live_videos = $live_videos;
    }

    private function setLiveEnd($live_end)
    {
        $this->live_end = $live_end;
    }

    private function setLiveTitle($live_title)
    {
        $this->live_title = $live_title;
    }

    /**
     * @param string $behind_the_scenes_channel
     */
    private function setBehindTheSceneChannel($behind_the_scenes_channel)
    {
        $this->behind_the_scenes_channel = $behind_the_scenes_channel;
    }

    /**
     * @param array $behind_the_scenes_videos
     */
    private function setBehindTheSceneVideos($behind_the_scenes_videos)
    {
        $this->behind_the_scenes_videos = $behind_the_scenes_videos;
    }

    private function setBehindTheSceneEnd($behind_the_scenes_end)
    {
        $this->behind_the_scenes_end = $behind_the_scenes_end;
    }

    private function setBehindTheSceneTitle($behind_the_scenes_title)
    {
        $this->behind_the_scenes_title = $behind_the_scenes_title;
    }

    /**
     * @param mixed $writer_id
     */
    private function setWriterId($writer_id)
    {
        $this->writer_id = $writer_id;
    }


    /**
     * check if the Live input is a channel 'or videos'
     *
     * @return bool
     */
    public function isLiveChannel()
    {
        return ($this->getLiveChannel()) ? true : false;
    }

    /**
     * @return mixed
     */
    public function getLiveChannel()
    {
        return $this->live_channel;
    }

    /**
     * check if Behind the scenes is a channel 'or videos'
     *
     * @return bool
     */
    public function isBehindTheScenesChannel()
    {
        return ($this->getBehindTheScenesChannel()) ? true : false;
    }

    /**
     * @return mixed
     */
    public function getBehindTheScenesChannel()
    {
        return $this->behind_the_scenes_channel;
    }

    /**
     * @param $input
     *
     * @return static
     */
    public static function parse($input)
    {
        return new static($input);
    }

    /**
     * @return mixed
     */
    public function getEpisodeTitle()
    {
        return $this->episode_title;
    }
    /**
     * @return mixed
     */
    public function getEpisodeSlugFormer()
    {
        return $this->episode_slug_former;
    }

    /**
     * check if this episode has Live input
     *
     * @return bool
     */
    public function isLiveEpisode()
    {
        if (is_null($this->getLiveChannel()) && is_null($this->getLiveVideos())) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getLiveVideos()
    {
        return $this->live_videos;
    }

    /**
     * check if this episode has Behind The Scenes input
     *
     * @return bool
     */
    public function isBehindTheScenesEpisode()
    {
        if (is_null($this->getBehindTheScenesChannel()) && is_null($this->getBehindTheSceneVideos())) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getBehindTheSceneVideos()
    {
        return $this->behind_the_scenes_videos;
    }

    /**
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->publish_date;
    }

    /**
     * @param mixed $publish_date
     */
    public function setPublishDate($publish_date)
    {
        $this->publish_date = $publish_date;
    }

    /**
     * @return mixed
     */
    public function getPublishState()
    {
        return $this->publish_state;
    }

    /**
     * @param mixed $publish_state
     */
    public function setPublishState($publish_state)
    {
        $this->publish_state = $publish_state;
    }

    /**
     * @return mixed
     */
    public function getSectionId()
    {
        return $this->section_id;
    }

    /**
     * @return mixed
     */
    public function getLiveEnds()
    {
        return $this->live_end;
    }
    /**
     * @return mixed
     */
    public function getLiveTitles()
    {
        return $this->live_title;
    }

    /**
     * @return mixed
     */
    public function getBehindTheScenesEnds()
    {
        return $this->behind_the_scenes_end;
    }

    /**
     * @return mixed
     */
    public function getBehindTheScenesTitles()
    {
        return $this->behind_the_scenes_title;
    }

    /**
     * @return mixed
     */
    public function getWriterId()
    {
        return $this->writer_id;
    }

    /**
     * @param mixed $section_id
     */
    private function setSectionId($section_id)
    {
        $this->section_id = $section_id;
    }

}
