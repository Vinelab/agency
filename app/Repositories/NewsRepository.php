<?php namespace Agency\Repositories;

use Agency\Contracts\Cms\Repositories\SectionRepositoryInterface;
use Agency\Contracts\NewsRepositoryInterface;
use Agency\Contracts\PhotosServiceInterface;
use Agency\Contracts\WriterRepositoryInterface;
use Agency\News;
use Agency\Repositories\ImageRepository as PhotoRepository;
use Agency\Services\ContentService;

/**
 * Class NewsRepository
 *
 * @category Repository
 * @package  Agency\Repositories
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class NewsRepository extends Repository implements NewsRepositoryInterface
{

    /**
     * defines the content type, when queering a content
     */
    const TYPE = 'news';

    /**
     * @var \Fahita\News
     */
    protected $news;

    /**
     * @var \Fahita\Contracts\PhotosServiceInterface
     */
    protected $photos_service;

    /**
     * @var \Agency\Repositories\ImageRepository
     */
    protected $photos;

    /**
     * @var \Fahita\Contracts\WriterRepositoryInterface
     */
    protected $writers;

    /**
     * @var \Agency\Contracts\Cms\Repositories\SectionRepositoryInterface
     */
    protected $sections;

    /**
     * @param \Agency\News                                                  $news
     * @param \Agency\Contracts\PhotosServiceInterface                      $photos_service
     * @param \Agency\Contracts\Cms\Repositories\SectionRepositoryInterface $sections
     * @param \Agency\Contracts\WriterRepositoryInterface                   $writers
     * @param \Agency\Repositories\ImageRepository                          $photos
     * @param \Agency\Services\ContentService                               $content_service
     */
    public function __construct(
        News $news,
        PhotosServiceInterface $photos_service,
        SectionRepositoryInterface $sections,
        WriterRepositoryInterface $writers,
        PhotoRepository $photos,
        ContentService $content_service
    ) {
        $this->sections = $sections;
        $this->model = $this->news = $news;
        $this->photos_service = $photos_service;
        $this->writers = $writers;
        $this->photos = $photos;
        $this->content_service = $content_service;
    }

    /**
     * @param       $title
     * @param       $slug_former
     * @param       $slug
     * @param       $body
     * @param       $featured
     * @param       $featured_date
     * @param       $publish_date
     * @param       $publish_state
     * @param array $relations
     *
     * @return mixed
     */
    public function createWith(
        $title,
        $slug,
        $body,
        $featured,
        $featured_date,
        $publish_date,
        $publish_state,
        $relations = []
    ) {
        return $this->news->createWith([
            'title'         => $title,
            'slug'          => $slug,
            'body'          => $body,
            'featured'      => $featured,
            'featured_date' => $featured_date,
            'publish_date'  => $publish_date,
            'publish_state' => $publish_state,
            'type'          => self::TYPE,
        ], $relations);
    }

    /**
     * @param $category
     * @param $limit
     * @param $relations
     *
     * @return mixed
     */
    public function forSection($category, $limit, $relations)
    {
        return $this->getData($category, $limit, $relations);
    }

    /**
     * get news of this category/section
     * {category is a child section of a section}
     *
     * @param        $category
     * @param        $limit
     * @param array  $relations
     * @param bool   $is_published
     * @param string $order_by
     * @param string $sorting
     *
     * @return mixed
     */
    public function getData(
        $category,
        $limit,
        $relations = [],
        $is_published = false,
        $order_by = 'updated_at',
        $sorting = 'DESC'
    ) {
        if ($is_published) {
            $query = $this->news->published();
        } else {
            $query = $this->news;
        }

        if ($category) {
            $query = $this->model->whereHas('section', function ($q) use ($category) {
                return $q->where('id', '=', $category->id);
            });
        }

        if (is_array($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query = $query->with($relation);
            }
        }

        $query = $query->orderBy($order_by, $sorting);

        return $query->paginate($limit);
    }

    /**
     * @param $category
     * @param $limit
     * @param $relations
     *
     * @return mixed
     */
    public function forSectionPublished($category, $limit, $relations)
    {
        return $this->getData($category, $limit, $relations, true, 'publish_date');
    }

    /**
     * @param       $idOrSlug
     * @param       $title
     * @param       $slug
     * @param       $body
     * @param       $publish_date
     * @param       $publish_state
     * @param array $relations
     */
    public function update(
        $idOrSlug,
        $title,
        $slug,
        $body,
        $featured,
        $featured_date,
        $publish_date,
        $publish_state,
        $relations = []
    ) {
        $news = $this->findBy('slug', $idOrSlug);

        $data = [
            'featured'      => $featured,
            'title'         => $title,
            'body'          => $body,
        ];

        $already_published = $this->content_service->isLimitedEditing($news);

        if (!$already_published) {
            $data['publish_date'] = $publish_date;
            $data['publish_state'] = $publish_state;

            if ($news->slug != $slug) {
                $data['slug'] = $slug;
            }
        }

        if (!is_null($featured_date) && $featured != $news->featured) {
            $data['featured_date'] = $featured_date;
        }

        $news->fill($data)->save();

        if (isset($relations['section'])) {
            $relation = $news->section()->associate($relations['section']);
            $relation->save();
        }

        if (isset($relations['writer'])) {
            $relation = $news->writer()->associate($relations['writer']);
            $relation->save();
        }

        if (isset($relations['coverPhoto'])) {
            $news->coverPhoto()->save($relations['coverPhoto']);
        }

        if (isset($relations['photos'])) {
            foreach ($relations['photos'] as $photo) {
                $news->photos()->save($photo);
            }
        }

        $news->save();
    }

    /**
     * detach the photo from the news article
     *
     * @param $newsSlug
     * @param $photoId
     *
     * @return mixed
     */
    public function detachPhoto($newsSlug, $photoId)
    {
        return $this->findBy('slug', $newsSlug)->photos()->detach((int)$photoId);
    }

    /**
     * @param $identifier
     *
     * @return mixed
     */
    public function delete($identifier)
    {
        $identifier_type = $this->isIdOrSlug($identifier);

        return $this->findBy($identifier_type, $identifier)->delete();
    }


    /**
     * collect form inputs that needs to be stored as relations to the model,
     * find the models (of the relation) and prepare them in an array
     * to be passed to the createWith function and the update function
     *
     * @param $relations
     *
     * @return array
     */
    public function relations($relations)
    {
        return array_merge(
            $this->coverRelations($relations),
            $this->writerRelations($relations),
            $this->sectionRelations($relations),
            $this->photosRelations($relations)
        );
    }

    /**
     * check if input exist then prepare the relation object and assign it to the array
     *
     * @param $relations
     *
     * @return array
     */
    public function coverRelations($relations)
    {
        $result = [];
        if (isset($relations['cover'])) {
            // read the cover image and convert it to photo model
            $result = ['coverPhoto' => $this->photos_service->create($relations['cover'])];
        }

        return $result;
    }

    /**
     * check if input exist then prepare the relation object and assign it to the array
     *
     * @param $relations
     *
     * @return array
     */
    public function writerRelations($relations)
    {
        $result = [];
        if (isset($relations['writer'])) {
            $result = ['writer' => $this->writers->findBy('id', $relations['writer'])];
        }

        return $result;
    }

    /**
     * check if input exist then prepare the relation object and assign it to the array
     *
     * @param $relations
     *
     * @return array
     */
    public function sectionRelations($relations)
    {
        $result = [];
        // prepare relations:
        if (isset($relations['section'])) {
            $result = ['section' => $this->sections->findBy('alias', $relations['section'])];
        }

        return $result;
    }

    /**
     * check if input exist then prepare the relation object and assign it to the array
     *
     * @param $relations
     *
     * @return array
     */
    public function photosRelations($relations)
    {
        $result = [];
        // this input "photos" holds photos URL's
        if (isset($relations['photos'])) {
            foreach ($relations['photos'] as $photo) {
                // read the photo URL's and convert it to photo model
                $result['photos'][] = $this->photos_service->create($photo);
            }
        }

        // this input "photos" holds photos URL's
        if (isset($relations['existing_photos'])) {
            foreach ($this->photos->find($relations['existing_photos']) as $photo) {
                // read the photo URL's and convert it to photo model
                $result['photos'][] = $photo;
            }
        }

        return $result;
    }

}
