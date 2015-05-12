<?php namespace Agency\Mappers;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Image;
use Vinelab\Api\MappableTrait;

class PhotoMapper {

    use MappableTrait;

    /**
     * @param \Najem\Contracts\Artists\PhotoInterface $photo
     *
     * @return array
     */
    public function map(Image $photo)
    {
        return [
            'original'  => (string) $photo->original,
            'small'     => (string) $photo->small,
            'thumbnail' => (string) $photo->thumbnail,
            'square'    => (string) $photo->square,
        ];
    }
}
