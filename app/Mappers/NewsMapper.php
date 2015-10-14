<?php namespace Agency\Mappers;

use Helper;
use Illuminate\Support\Facades\Config;
use Vinelab\Api\MappableTrait;
use Agency\News;
use \Editor;

/**
 * Class NewsMapper
 *
 * @category Data Mapper
 * @package  Agency\Mappers
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class NewsMapper
{

    use MappableTrait;

    private $url;

    /**
     * @param \Agency\Mappers\PhotoMapper  $photo_mapper
     * @param \Agency\Mappers\WriterMapper $writer_mapper
     */
    public function __construct(
        PhotoMapper $photo_mapper,
        WriterMapper $writer_mapper
    ) {
        $this->photo_mapper = $photo_mapper;
        $this->writer_mapper = $writer_mapper;

        $this->url = Config::get('app.url') . '/news/';
    }

    /**
     * @param \Agency\News $news
     *
     * @return array
     */
    public function map(News $news)
    {
        return [
            'id'           => (int) $news->id,
            'title'        => (string) $news->title,
            'slug'         => (string) $news->slug,
            'featured'     => (bool) $news->featured,
            'publish_date' => (string) Helper::formatDate($news->publish_date),
            'share_url'    => (string) $this->shareUrl($news),
            'body'         => Editor::json($news->body),
            'photos'       => $this->photos($news),
            'cover'        => $this->cover($news),
            'writer'       => $this->writer($news),
            'content_type' => (string) $news->type,
        ];
    }

    /**
     * @param \Agency\News $news
     *
     * @return string
     */
    private function shareUrl(News $news)
    {
        return $this->url . $news->slug;
    }

    /**
     * @param \Agency\News $news
     *
     * @return array
     */
    private function photos(News $news)
    {
        return array_map([$this->photo_mapper, 'map'], $news->photos->all());
    }

    /**
     * @param \Agency\News $news
     *
     * @return array
     */
    private function cover(News $news)
    {
        return (!is_null($news->coverPhoto)) ? $this->photo_mapper->map($news->coverPhoto) : [];
    }

    /**
     * @param \Agency\News $news
     *
     * @return array
     */
    private function writer(News $news)
    {
        return (!is_null($news->writer)) ? $this->writer_mapper->map($news->writer) : [];
    }

}
