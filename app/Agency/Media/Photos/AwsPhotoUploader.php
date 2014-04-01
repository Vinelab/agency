<?php namespace Agency\Media\Photos;

use Aws\S3\S3Client as S3;
use Intervention\Image\Image;
use Illuminate\Config\Repository as Config;
use Symfony\Component\HttpFoundation\File\UploadedFile as File;
use Agency\Media\Photos\Contracts\PhotoUploaderInterface;

class AwsPhotoUploader implements PhotoUploaderInterface {

    /**
     * Create a new AWS Photo Uploader instance.
     *
     * @param Illuminate\Config\Repository $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        $configuration = $this->config->get('aws.s3');

        $this->bucket = $configuration['bucket'];

        $credentials = $configuration['credentials'];

        $this->s3 = S3::factory([
            'key'    => $credentials['key'],
            'secret' => $credentials['secret']
        ]);
    }

    /**
     * Upload a photo.
     *
     * @param  Symfony\Component\HttpFoundation\File\UploadedFile   $file
     * @param  string $directory
     * @param  string $name
     * @param  string $mime
     * @return Guzzle\Service\Resource\Model
     */
    public function upload(Image $image, $directory, $name, $mime)
    {
        return $this->s3->putObject([
            'Bucket'      => $this->bucket($directory),
            'Key'         => $name,
            'ACL'         => 'public-read',
            'Body'        => $image,
            'ContentType' => $mime
        ]);
    }

    /**
     * Generates the bucket (directory)
     * that should contain uploaded photos.
     *
     * @param  string $directory The directory name excluding the S3 bucket name
     * @return string
     */
    public function bucket($directory)
    {
        return $this->bucket . '/' . trim($directory, '/');
    }
}