<?php namespace Agency\Media\Photos;

use Agency\Media\Photos\Photo;
use Agency\Media\Photos\UploadedPhoto;
use Agency\Media\Photos\PhotosCollection;
use Agency\Media\Photos\UploadedPhotosCollection;
use Illuminate\Config\Repository as Config;
use Agency\Media\Photos\Contracts\UploadInterface as Upload;
use Agency\Media\Photos\Contracts\PhotoEditorInterface;
use Agency\Media\Photos\Contracts\PhotoUploaderInterface;

class Manager implements Contracts\ManagerInterface {

    /**
     * The photo editor.
     *
     * @var Agency\Media\Photos\Contracts\PhotoEditorInterface
     */
    protected $editor;

    /**
     * The uploader.
     *
     * @var Agency\Media\Photos\Contracts\PhotoUploaderInterface
     */
    protected $uploader;

    /**
     * The configuration instance.
     *
     * @var Illuminate\Config\Repository
     */
    protected $config;

    /**
     * The upload factory instance.
     *
     * @var Agency\Media\Contracts\UploadInterface
     */
    protected $upload;

    /**
     * The photo factory instance.
     *
     * @var Agency\Media\Photos\Photo
     */
    protected $photo;

    /**
     * The file instance that is used to reference
     * the actual file as a photo.
     *
     * @var Agency\Media\Photos\File
     */
    protected $file;

    /**
     * Create a new Manager instance.
     *
     * @param Agency\Media\Photos\Contracts\PhotosUploaderInterface $uploader
     */
    public function __construct(Config $config,
                                Upload $upload,
                                Photo  $photo,
                                PhotoEditorInterface $editor,
                                PhotoUploaderInterface $uploader)
    {
        $this->config   = $config;
        $this->upload   = $upload;
        $this->photo    = $photo;
        $this->editor   = $editor;
        $this->uploader = $uploader;
    }

    /**
     * Upload a collection of photos.
     *
     * @param  Agency\Media\Photos\UploadedPhotosCollection $photo
     * @return void
     */
    public function upload(UploadedPhotosCollection $photos, $directory)
    {
        $config = $this->config->get('media.photos');

        $small_dimensions = $config['presets']['small'];
        $square_dimensions = $config['presets']['square'];
        $thumbnail_dimensions = $config['presets']['thumbnail'];

        // holds the resized images
        // to be passed to the uploader later on
        $resized = [];

        foreach($photos->toArray() as $original)
        {
            $processed = $this->resize($original, $small_dimensions, $thumbnail_dimensions, $square_dimensions);

            array_push($resized, $processed);
        }

        return $this->uploadPhotos($resized, $directory);
    }

    /**
     * @param \Agency\Media\Photos\UploadedPhoto $original
     * @param                                        $small_dimensions
     * @param                                        $thumb_dimensions
     * @param                                        $square_dimensions
     *
     * @return array
     */
    public function resize(UploadedPhoto $original, $small_dimensions, $thumb_dimensions, $square_dimensions)
    {
        /**
         * @todo  Handle exceptions and delete cached files when they occur.
         */
        // store the resized stuff here
        $resized = [];

        // trash: hold all the file paths that needs to be removed from filesystem (unliked)
        $trash = [];

        // extract photo data
        $file           = $original->file();
        $file_name      = $original->name();
        $file_path      = $original->path();
        $file_extention = $original->extension();

        $mime            = $original->mime();
        $original_width  = $original->width();
        $original_height = $original->height();
        $crop_width      = $original->cropWidth();
        $crop_height     = $original->cropHeight();
        $crop_x          = $original->cropX();
        $crop_y          = $original->cropY();

        $name = $this->nameFile($file_name, $file_extention);

        $original_photo = $this->editor->makePhoto($file_path);
        $original_photo->name = $file_name;

        $original_path = $this->editor->cache($original_photo);
        $trash[] = $original_path;

        // set original photo
        $resized['original'] = [
            'name' => $name,
            'file' => $original_photo,
            'mime' => $mime
        ];

        // determine whether this is a landscape or portrait image
        $orientation = $this->editor->orientation($original_width, $original_height);

        if ($orientation === 'landscape')
        {
            $small_width = $small_dimensions['width'];
            $small_height = $original_height;
        } else {
            $small_width = $original_width;
            $small_height = $small_dimensions['height'];
        }

        // generate the small photo
        $small_name = $this->nameFile($name, $file_extention, 'small', true);
        $small = $this->editor->scale($file_path, $small_width, $small_height);
        $small_path = $this->editor->cache($small);
        $trash[] = $small_path;

        // set the edited file's name
        $small->name = $file_name;

        $resized['small'] = [
            'file' => $small,
            'name' => $small_name,
            'mime' => $mime
        ];
        // crop photo according to the cropping dimensions
        // to get a 3/2 aspect ratio
        $cropped = $this->editor->crop($file_path, $crop_width, $crop_height, $crop_x, $crop_y);

        // cache the generated crop
        $cropped_path = $this->editor->cache($cropped);
        $trash[] = $cropped_path;

        // generate thumbnail
        $thumb_name = $this->nameFile($name, $file_extention, 'thumb', true);
        $thumb  = $this->editor->resize(
            $cropped_path,
            $thumb_dimensions['width'],
            $thumb_dimensions['height']
        );

        // set the edited file's name
        $thumb->name = $file_name;

        // cache the generated thumbnail
        $thumb_path = $this->editor->cache($thumb);
        $trash[] = $thumb_path;

        // set thumbnail photo
        $resized['thumbnail'] = [
            'name' => $thumb_name,
            'file' => $thumb,
            'mime' => $mime
        ];
        // generate square
        $square_name = $this->nameFile($name, $file_extention, 'sq', true);
        $square = $this->editor->crop(
            $thumb_path,
            $square_dimensions['width'],
            $square_dimensions['height'],
            $crop_x = 50
        );
        $square->name = $file_name;

        $square_path = $this->editor->cache($square);
        $trash[] = $square_path;

        $resized['square'] = [
            'name' => $square_name,
            'file' => $square,
            'mime' => $mime
        ];

        // remove cached files
        array_map(function($path) {
            @unlink($path);
        }, $trash);

        return $resized;
    }

    public function uploadPhotos($photos, $directory)
    {
        $uploads = new PhotosCollection;

        foreach ($photos as $presets)
        {
            // photos have different presets, upload each of them
            foreach ($presets as $preset => $photo)
            {
                // extract photo info
                $file = $photo['file'];
                $name = $file->name;

                // upload
                $response = $this->uploader->upload($photo['file'], $directory, $photo['name'], $photo['mime']);

                // map upload to a photo
                $upload = $this->upload->make($response);
                $photo  = $this->photo->make($upload);

                // add to photos collection
                // check if the collection already contains
                // presets for this photo, if so, well, meh, MERGE WE WILL!
                if ($uploads->has($name))
                {
                    $existing = $uploads->get($name);
                    $uploads->put($name, array_merge($existing, [$preset => $photo]));
                } else {
                    $uploads->put($name, [$preset => $photo]);
                }
            }
        }

        return $uploads;
    }

    public function nameFile($name, $extension, $preset = null, $preserve_name = false)
    {
        if ($preserve_name)
        {
            // remove extension
            $name = str_replace('.' . $extension, '', $name);

        } else {

            $name = uniqid();
        }

        if ( ! is_null($preset))
        {
            $name .= '.' . $preset;
        }

        return $name . '.' . $extension;
    }
}
