<?php namespace Agency\Mappers;

use Agency\Album;
use Agency\Episode;
use Agency\News;
use Agency\Content;
use \Editor;

/**
 * Class ContentMapper
 *
 * @category Data Mapper
 * @package  Agency\Mappers
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ContentMapper
{

    const TYPE_NEWS = 'news';
    const TYPE_ALBUM = 'album';
    const TYPE_EPISODE = 'episode';

    /**
     * @var
     */
    private $news_mapper;

    /**
     * @var
     */
    private $album_mapper;

    /**
     * @var
     */
    private $episode_mapper;


    /**
     * @param \Agency\Mappers\NewsMapper    $news_mapper
     * @param \Agency\Mappers\AlbumMapper   $album_mapper
     * @param \Agency\Mappers\EpisodeMapper $episode_mapper
     */
    public function __construct(
        NewsMapper $news_mapper,
        AlbumMapper $album_mapper,
        EpisodeMapper $episode_mapper
    ) {
        $this->news_mapper = $news_mapper;
        $this->album_mapper = $album_mapper;
        $this->episode_mapper = $episode_mapper;
    }

    /**
     * @param \Agency\Content $content
     *
     * @return array
     */
    public function map(Content $content)
    {
        return $this->content($content);
    }

    /**
     * get the content type and call the right mapper
     *
     * @param $content
     *
     * @return array
     */
    private function content($content)
    {
        $data = [];

        switch ($content->type) {
            case self::TYPE_NEWS:
                $data = $this->news_mapper->map((new News)->forceFill($content->toArray()));
                break;
            case self::TYPE_ALBUM:
                $data = $this->album_mapper->map((new Album)->forceFill($content->toArray()));
                break;
            case self::TYPE_EPISODE:
                $data = $this->episode_mapper->map((new Episode)->forceFill($content->toArray()));
                break;
        }

        return $data;
    }

}
