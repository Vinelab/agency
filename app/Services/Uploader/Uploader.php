<?php namespace Agency\Services\Uploader;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Agency\Exceptions\DriverNotSupportedException;
use App;

class Uploader{

    /**
     * @var uplaoder instance
     */
    protected $uploader;

    /**
     * at run time create an instance of an uploader based on the parameter,
     * by default is AWS.
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function upload($driver = 'aws')
    {
        return $this->createUploader($driver)->upload();
    }


    /**
     * @param $uploader
     *
     * @return mixed
     */
    private function createUploader($driver)
    {
        switch($driver){
            case 'aws':
                return $this->uploader = App::make('Agency\Services\Uploader\AwsUploader');
                break;
            default:
                throw new DriverNotSupportedException('The uploader {$uploader} is not supported.');
        }
    }


    /**
     * return the uploader instance
     *
     * @return uploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }

}
