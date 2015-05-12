<?php namespace Agency\Media\Photos;

use DateTime;
use Guzzle\Service\Resource\Model as Response;

class AwsUpload implements Contracts\UploadInterface {

    /**
     * The payload data.
     *
     * @var array
     */
    protected $data;

    public function make(Response $response)
    {
        $upload = new static;

        $data = $response->toArray();
        $data['uploaded_at'] = new DateTime();

        $upload->setData($data);

        return $upload;
    }

    public function data()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function time()
    {
        return $this->data['uploaded_at'];
    }

    public function getId()
    {
        return $this->data['RequestId'];
    }

    public function getURL()
    {
        return $this->data['ObjectURL'];
    }

    public function getETag()
    {
        return $this->data['ETag'];
    }
}
