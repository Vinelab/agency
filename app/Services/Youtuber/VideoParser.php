<?php namespace Agency\Services\Youtuber;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Contracts\Repositories\ImageRepositoryInterface;
use Agency\Repositories\Contracts\VideoRepositoryInterface;

class VideoParser
{

    protected $videos;

    protected $images;

    /**
     * @param \Agency\Repositories\Contracts\VideoRepositoryInterface $videos
     * @param \Agency\Contracts\Repositories\ImageRepositoryInterface $images
     */
    public function __construct(
        VideoRepositoryInterface $videos,
        ImageRepositoryInterface $images
    ) {
        $this->videos = $videos;
        $this->images = $images;
    }

    /**
     * @param       $videos
     * @param array $episode_input
     * @param null  $video_type
     *
     * @return array
     */
    public function parse($videos, $episode_input = [], $video_type = null)
    {

        return array_map(function ($video) use ($episode_input, $video_type) {

            // find the END input and the Title of this video
            $extra = $this->getVideoExtraData($video_type, $episode_input, $video);

            return $this->videos->createWith(
                $video->id,
                $video->url,
                $extra['end'],
                $extra['title'] ? $extra['title'] : $video->snippet['title'],
                $video->snippet['description'],
                true, // sync_enabled is true by default and for the purpose of this project
                $video->synced_at,
                $video->etag,
                [
                    'thumbnail' => $this->images->createWithUri(
                        (isset($video->thumbnails['maxres']) ? $video->thumbnails['maxres'] : $video->thumbnails['high']),
                        (isset($video->thumbnails['medium']) ? $video->thumbnails['medium'] : $video->thumbnails['default']),
                        (isset($video->thumbnails['standard']) ? $video->thumbnails['standard'] : $video->thumbnails['high']),
                        (isset($video->thumbnails['high']) ? $video->thumbnails['high'] : $video->thumbnails['default'])
                    )
                ]
            );

        }, $videos);
    }


    /**
     * TEMPORARY quick modification
     * this function tries to get the END input value from the input for the video parameter
     *
     * @param $video_type
     * @param $episode_input
     * @param $video
     *
     * @return null
     */
    private function getVideoExtraData($video_type, $episode_input, $video)
    {
        $end_value = null;
        $title_value = null;

        if($video_type == 'live' && $episode_input->getLiveVideos()){
            $live_index = null;
            foreach ($episode_input->getLiveVideos() as $index => $video_url) {
                if ($video->url == $video_url) {
                    $live_index = $index;
                }
            }
            $end_value = $episode_input->getLiveEnds()[$live_index];
            $title_value = $episode_input->getLiveTitles()[$live_index];
        }

        if($video_type == 'behindTheScenes' && $episode_input->getBehindTheSceneVideos()){
            $behind_index = null;
            foreach ($episode_input->getBehindTheSceneVideos() as $index => $video_url) {
                if ($video->url == $video_url) {
                    $behind_index = $index;
                }
            }
            $end_value = $episode_input->getBehindTheScenesEnds()[$behind_index];
            $title_value = $episode_input->getBehindTheScenesTitles()[$behind_index];
        }

        return [
            'end' => $end_value,
            'title' => $title_value
        ];
    }

}
