<?php namespace Agency\Repositories;

use Agency\Contracts\ContentRepositoryInterface;
use Agency\Contracts\WriterRepositoryInterface;
use Agency\Content;

/**
 * Class ContentRepository
 *
 * @category Repository
 * @package  Agency\Repositories
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ContentRepository extends Repository implements ContentRepositoryInterface
{

    /**
     * @var \Agency\Contracts\WriterRepositoryInterface
     */
    protected $writers;

    /**
     * @var \Agency\Content
     */
    protected $content;


    /**
     * @param \Agency\Content                             $content
     * @param \Agency\Contracts\WriterRepositoryInterface $writers
     */
    public function __construct(
        Content $content,
        WriterRepositoryInterface $writers
    ) {
        $this->model = $this->content = $content;
        $this->writers = $writers;
    }

    /**
     * @param $limit
     * @param $relations
     *
     * @return mixed
     */
    public function forSection($limit, $relations)
    {
        return $this->getData($limit, $relations, false);
    }

    /**
     * @param $limit
     * @param $relations
     *
     * @return mixed
     */
    public function forSectionPublished($limit, $relations)
    {
        return $this->getData($limit, $relations, true);
    }

    /**
     * @param $limit
     * @param $relations
     *
     * @param $is_featured
     *
     * @return mixed
     */
    public function forSectionPublishedAndFeatured($limit, $relations, $is_featured)
    {
        return $this->getData($limit, $relations, true, true, $is_featured, 'featured_date', 'DESC');
    }


    /**
     * get content of this category/section
     * {category is a child section of a section}
     *
     * @param        $limit
     * @param array  $relations
     * @param bool   $is_published
     * @param bool   $filter_featured
     * @param bool   $is_featured
     * @param string $order_by
     * @param string $sorting
     *
     * @return mixed
     */
    private function getData(
        $limit,
        $relations = [],
        $is_published = false,
        $filter_featured = false,
        $is_featured = false,
        $order_by = 'publish_date',
        $sorting = 'DESC'
    ) {

        if ($is_published) {
            $query = $this->content->published();
        } else {
            $query = $this->content;
        }

        if (is_array($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query = $query->with($relation);
            }
        }

        if ($filter_featured) {
            $query = $query->where('featured', $is_featured);
        }

        $query = $query->orderBy($order_by, $sorting);

        return $query->paginate($limit);
    }

}
