<?php namespace Agency\RealTime;

use App;
use Vinelab\Minion\Dictionary;
use Agency\Contracts\ContentInterface;
use Agency\Content as PublishedContent;
use Agency\Exceptions\InvalidContentException;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Content implements ContentInterface {

    const TYPE_ANY = 'content';
    const TYPE_VIDEO = 'video';
    const TYPE_ARTICLE = 'article';
    const TYPE_PHOTO_ALBUM = 'photoalbum';

    /**
     * The attributes of this content instance.
     *
     * @var array
     */
    protected $attributes = [];

    public function __construct(Dictionary $data)
    {
        $this->validate($data);
        $this->attributes = (array) $data->content;
    }

    /**
     * Get the content identifier.
     *
     * @return string
     */
    public function id()
    {
        return $this->attributes['id'];
    }

    /**
     * Get the content type.
     *
     * @return string
     */
    public function type()
    {
        return $this->attributes['type'];
    }

    /**
     * Get a new Content instance.
     *
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return \Fahita\RealTime\Content
     */
    public static function make(Dictionary $data)
    {
        return new static($data);
    }

    /**
     * Get a new Content instance from an instance of \Agency\Content.
     *
     * @param  \Agency\Content $content
     *
     * @return \Agency\RealTime\Content
     */
    public static function makeFromContent(PublishedContent $content)
    {
        return new static(Dictionary::make(['content' => ['id' => $content->getKey(), 'type' => self::TYPE_ANY]]));
    }

    /**
     * Validate the given data
     *
     * @param array $data
     *
     * @return boolean
     *
     * @throws \Fahita\Exceptions\InvalidContentException
     */
    public function validate(Dictionary $data)
    {
        if (! isset($data->content) || empty($data->content) || ! is_object($data->content)) {
            throw new InvalidContentException('The "content" attribute is required with "id" and "type"');
        }

        return App::make('Agency\Contracts\Validators\ContentValidatorInterface')
            ->validate((array) $data->content);
    }
}
