<?php namespace Agency\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 * @author Abed Halawi <abed.halawi@vinelab.com>
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Contracts\HelperInterface;
use Agency\Contracts\ImageInterface;
use Agency\Contracts\Repositories\ImageRepositoryInterface;
use Agency\Image;
use Agency\Media\Photos\Photo;
use DB;
use File;

class ImageRepository extends Repository implements ImageRepositoryInterface{

    /**
     * @param \Agency\Contracts\ImageInterface  $image
     * @param \Agency\Contracts\HelperInterface $helper
     */
    public function __construct(
        ImageInterface $image,
        HelperInterface $helper
    ) {
        $this->image = $this->model = $image;
        $this->helper = $helper;
    }

    /**
     * @param \Agency\Media\Photos\Photo $original
     * @param \Agency\Media\Photos\Photo $thumbnail
     * @param \Agency\Media\Photos\Photo $small
     * @param \Agency\Media\Photos\Photo $square
     * @param null                       $description
     *
     * @return mixed
     */
    public function create(
        Photo $original,
        Photo $thumbnail,
        Photo $small,
        Photo $square,
        $description = null
    ) {
        return $this->image->create([
            'original'    => $original->url,
            'thumbnail'   => $thumbnail->url,
            'small'       => $small->url,
            'square'      => $square->url,
            'description' => $description
        ]);
    }


    /**
     * create with the URI's
     *
     * @param string $original
     * @param string $thumbnail
     * @param string $small
     * @param string $square
     *
     * @return mixed
     */
    public function createWithUri(
        $original,
        $thumbnail,
        $small,
        $square
    ) {
        return $this->image->create([
            'original'  => $original,
            'thumbnail' => $thumbnail,
            'small'     => $small,
            'square'    => $square
        ]);
    }

    /**
     * @param                            $id
     * @param \Agency\Media\Photos\Photo $original
     * @param \Agency\Media\Photos\Photo $thumbnail
     * @param \Agency\Media\Photos\Photo $small
     * @param \Agency\Media\Photos\Photo $square
     *
     * @return \Agency\Repositories\Illuminate\Database\Eloquent\Model
     */
    public function update(
        $id,
        Photo $original,
        Photo $thumbnail,
        Photo $small,
        Photo $square
    ) {
        $image = $this->find($id);
        $image->original = $original->url;
        $image->thumbnail = $thumbnail->url;
        $image->small = $small->url;
        $image->square = $square->url;
        $image->save();

        return $image;
    }

    /**
     * @param $guid
     *
     * @return mixed
     */
    public function getThumbnail($guid)
    {
        return $this->image
            ->where('guid', '=', $guid)
            ->where('preset', '=', $this->image->presetType('thumbnail'))
            ->first();
    }

    /**
     * @param $guid
     *
     * @return mixed
     */
    public function getByGuid($guid)
    {
        return $this->image->where('guid', '=', $guid)->get();
    }

    /**
     * @override
     *
     * @param {array|int|string} $image_ids
     *
     * @return boolean
     */
    public function remove($image_ids)
    {
        return $this->image->destroy($image_ids);
    }

    /**
     * @param array $images_without_original
     *
     * @return mixed
     */
    public function store($images_without_original)
    {
        return $this->image->insert($images_without_original);
    }

    /**
     * return preset type
     *
     * @param  string $type
     *
     * @return string
     */
    public function presetType($type)
    {
        return $this->image->presetType($type);
    }

    /**
     * return Image instance
     *
     * @param  string $url
     * @param  string $preset
     * @param  string $guid
     *
     * @return Agency\Image
     */
    public function newImage($url, $preset, $guid)
    {
        return new Image([ 'url' => $url, 'preset' => $preset, 'guid' => $guid ]);
    }

    /**
     * @param $num
     *
     * @return mixed
     */
    public function paginate($num)
    {
        return $this->image->paginate($num);
    }

    /**
     * @param int    $num
     * @param string $order_by
     * @param string $sorting
     *
     * @return mixed
     */
    public function getAll($num = 10, $order_by = 'updated_at', $sorting = 'ASC')
    {
        return $this->image->orderBy($order_by, $sorting)->paginate($num);
    }

}
