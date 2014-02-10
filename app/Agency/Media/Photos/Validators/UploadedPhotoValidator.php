<?php namespace Agency\Media\Photos\Validators;

use Illuminate\Validation\Factory as Validator;
use Agency\Media\Photos\Exceptions\PhotoUploadException;
use Agency\Media\Photos\Exceptions\InvalidMediaTypeException;
use Agency\Media\Photos\Exceptions\UploadedPhotoMetaException;
use Agency\Media\Photos\Exceptions\PhotoFormatNotAllowedException;
use Symfony\Component\HttpFoundation\File\UploadedFile as File;

class UploadedPhotoValidator {

    /**
     * Create a new UploadedPhotoValidator instance.
     *
     * @param Illuminate\Validation\Factory $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate a newly uploaded photo.
     *
     * @param  Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param  array $meta
     * @return void
     */
    public function validate(File $file, $meta)
    {
        $this->validateFile($file);

        $this->validateMeta($meta);
    }

    /**
     * Determines whether a passed photo file
     * is valid.
     * @throws  InalidMediaTypeException If the file extension isn't jpeg, jpg, png, bmp or gif
     *
     * @param  Symfony\Component\HttpFoundation\File\UploadedFile   $file
     * @return boolean
     */
    public function validateFile(File $file)
    {
        if ( ! $file->isValid())
        {
            throw new PhotoUploadException('error occured while uploading photo' , $file->getError());
        }

        $input = ['extension' => $file->guessExtension()];
        $rules = ['extension' => 'in:jpeg,jpg,png,bmp,gif'];

        $validation = $this->validator->make($input, $rules);

        if ($validation->fails())
        {
            throw new InvalidMediaTypeException($file->guessExtension());
        }

        return true;
    }

    /**
     * Validates the meta data of an uploaded photo.
     *
     * @throws  UploadedPhotoMetaException If the meta data were wrong
     *
     * @param  array $meta
     * @return boolean
     */
    public function validateMeta($meta)
    {
        $rules = [
            'width'       => 'required|integer|between:300,9999',
            'height'      => 'required|integer|between:200,9999',
            'crop_width'  => 'required|integer|between:300,9999',
            'crop_height' => 'required|integer|between:200,9999',
            'crop_x'      => 'required|integer|between:0,9999',
            'crop_y'      => 'required|integer|between:0,9999'
        ];

        $validation = $this->validator->make($meta, $rules);

        if ($validation->fails())
        {
            throw new UploadedPhotoMetaException(implode(' ',$validation->messages()->all()));
        }
        /**
         * @todo Validate whether the cropping area is within the Photo dimensions
         */
        return true;
    }
}