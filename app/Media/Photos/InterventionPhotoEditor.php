<?php namespace Agency\Media\Photos;

use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;
use Agency\Media\Photos\Exceptions\InvalidPhotoInstanceException;

class InterventionPhotoEditor implements Contracts\PhotoEditorInterface {

    public function makePhoto($path)
    {
        return Image::make($path);
    }

    /**
     * Crop photo file according
     * @param  string  $file
     * @param  integer $width  Cropping width
     * @param  integer $height Cropping height
     * @param  integer $x      Cropping x coordinate
     * @param  integer $y      Cropping y coordinate
     * @return Intervention\Image\Image
     */
    public function crop($file, $width, $height, $x = 0, $y = 0)
    {
        return Image::make($file)->crop($width, $height, $x, $y);
    }

    /**
     * Resize an image according to given
     * width and height.
     *
     * @param  string  $file
     * @param  integer $with
     * @param  integer $height
     * @return Intervention\Image\Image
     */
    public function resize($file, $width, $height)
    {
        return Image::make($file)->resize($width, $height);
    }

    /**
     * Scale an image according to specified
     * width and height.
     *
     * @param  string $file
     * @param  integer $width
     * @param  integer $height
     * @return Intervention\Image\Image
     */
    public function scale($file, $width, $height)
    {
        return Image::make($file)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    }

    /**
     * Determines the orientation of an image
     * according to its width and height.
     *
     * @param  integer $width
     * @param  integer $height
     * @return string Possible orientations are 'landscape' and 'portrait'
     */
    public function orientation($width, $height)
    {
        if ($width >= $height)
        {
            return 'landscape';
        }

        return 'portrait';
    }

    /**
     * Caches an image. Saves it to disk
     * and returns the cache path.
     *
     * @param  Intervention\Image\Image $photo
     * @return string
     */
    public function cache($photo)
    {

        if ( ! $photo instanceof InterventionImage)
        {
            throw new InvalidPhotoInstanceException(
                'expected ( Intervention\Image\Image)  got  ( ' . get_class($photo) . ' ).'
            );
        }

        $path = $this->cachePath($photo->filename);
        $photo->save($path);

        return $path;
    }

    /**
     * Generates the cache path.
     *
     * @param  string $filename
     * @return string
     */
    public function cachePath($filename)
    {
        return storage_path() . '/framework/cache/' . $filename;
    }
}
