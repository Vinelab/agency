<?php namespace Agency\Media\Photos;

use App;
use Agency\Media\Photos\Validators\UploadedPhotoValidator as Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedPhoto implements Contracts\UploadedPhotoInterface {

    /**
     * The uploaded file.
     *
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $uploaded_file;

    /**
     * The meta data related to the uploaded photo.
     * i.e. Cropping dimensions etc.
     *
     * @var array
     */
    protected $meta = array();

    /**
     * Create an UploadedPhoto instance.
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param array $meta
     */
    public function __construct(UploadedFile $uploaded_file, $meta, Validator $validator)
    {
        $this->validator = $validator;
        $this->uploaded_file = $uploaded_file;
        $this->meta = $meta;
    }

    /**
     * Create and instance of this class.
     *
     * @param  Symfony\Component\HttpFoundation\File\UploadedFile   $file
     * @param  array $meta
     * @param  mixed $validator A validator instance
     * @return Agency\Media\Photos\UploadedPhoto
     */
    public static function make(UploadedFile $file, $meta, $validator = null)
    {
        if (is_null($validator))
        {
            $validator = new Validator(App::make('validator'));
        }
        
        return new static($file, $meta, $validator);
    }

    /**
     * Validate everything about this photo.
     *
     * @return Agency\Media\Photos\UploadedPhoto
     */
    public function validate()
    {
        $this->validator->validate($this->uploaded_file, $this->meta);

        return $this;
    }

    /**
     * Returns the photo uploaded file.
     *
     * @return Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function file()
    {
        return $this->uploaded_file;
    }

    /**
     * Returns the real file name
     * as named on the client machine.
     *
     * @return string
     */
    public function name()
    {
        return $this->uploaded_file->getClientOriginalName();
    }

    public function path()
    {
        return $this->uploaded_file->getRealPath();
    }

    /**
     * Returns the photo file extension.
     *
     * @return string
     */
    public function extension()
    {
        return $this->uploaded_file->guessExtension();
    }

    /**
     * Returns the MIME type of photo.
     *
     * @return string
     */
    public function mime()
    {
        return $this->uploaded_file->getMimeType();
    }

    /**
     * Returns the meta data.
     *
     * @return array
     */
    public function meta()
    {
        return $this->meta;
    }

    /**
     * Returns the photo width from meta.
     *
     * @return int
     */
    public function width()
    {
        return $this->meta['width'];
    }

    /**
     * Returns the photo height from meta.
     *
     * @return int
     */
    public function height()
    {
        return $this->meta['height'];
    }

    /**
     * Returns the photo cropping width from meta.
     *
     * @return int
     */
    public function cropWidth()
    {
        return $this->meta['crop_width'];
    }

    /**
     * Returns the photo cropping height from meta.
     *
     * @return int
     */
    public function cropHeight()
    {
        return $this->meta['crop_height'];
    }

    /**
     * Returns the photo cropping X coordinate from meta.
     *
     * @return int
     */
    public function cropX()
    {
        return $this->meta['crop_x'];
    }

    /**
     * Returns the photo cropping Y coordinate from meta.
     *
     * @return int
     */
    public function cropY()
    {
        return $this->meta['crop_y'];
    }
}