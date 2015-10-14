<?php namespace Agency\Services;

use Agency\Content;
use Agency\Contracts\ContentServiceInterface;
use Agency\Contracts\ContentRepositoryInterface;
use Agency\Contracts\HelperInterface;
use Carbon\Carbon;
use Input;

/**
 * Class ContentService
 *
 * @category Service
 * @package  Agency\Services
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ContentService extends AgencyService implements ContentServiceInterface
{

    /**
     * @param \Agency\Contracts\ContentRepositoryInterface $content
     * @param \Agency\Contracts\HelperInterface            $helper
     */
    public function __construct(
        ContentRepositoryInterface $content,
        HelperInterface $helper
    ) {
        $this->content = $content;
        $this->helper = $helper;
    }

    /**
     * @param int   $limit
     * @param array $relations
     *
     * @return mixed
     */
    public function all($limit = 10, $relations = [])
    {
        // get content of this section
        return $this->content->forSection($limit, $relations);
    }

    /**
     * @param int   $limit
     * @param array $relations
     *
     * @return mixed
     * @internal param null $featured
     *
     */
    public function allPublished($limit = 10, $relations = [])
    {
        // get content of this section and published
        return $this->content->forSectionPublished($limit, $relations);
    }

    /**
     * @param int   $limit
     * @param array $relations
     * @param null  $is_featured
     *
     * @return mixed
     */
    public function allPublishedAndFeatured($limit = 10, $relations = [], $is_featured)
    {
        // get content of this section and published and featured
        return $this->content->forSectionPublishedAndFeatured($limit, $relations, $is_featured);
    }

    /**
     * determine a content title and publish_date can be edited
     *
     * - if editing (return false)
     * - if published and publish date > now (return false)
     * - if published and publish date <= now + XX minutes (return true)
     *
     * @param     $entity
     * @param int $minutes
     *
     * @return bool
     */
    public function isLimitedEditing($entity, $minutes = 10)
    {
        $limited = false;

        if ($entity->publish_state == 'published'
            && $entity->publish_date <= Carbon::now(config('app.timezone'))->addMinutes($minutes)->toDateTimeString()
        ) {
            $limited = true;
        }

        return $limited;
    }

}
