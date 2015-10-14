<?php namespace Agency\Services\Youtuber;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Vinelab\Youtube\Facades\Youtube;
use Vinelab\Youtube\Helpers\YoutubeUrlParser;

class YoutubeManager{

    /**
     * @param $url
     *
     * @return mixed
     */
    public function getPlaylistVideos($url)
    {
        return Youtube::playlist($url);
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public function getChannelVideos($url)
    {
        return Youtube::channel($url);
    }

    /**
     * @param $urls string|array or strings
     *
     * @return mixed
     */
    public function getVideos($urls)
    {
        return Youtube::videos($urls);
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public function sync($model)
    {
        return Youtube::sync($model);
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getPlaylistIdFromUrl($url)
    {
        return YoutubeUrlParser::parsePlaylistUrl($url);
    }

}
