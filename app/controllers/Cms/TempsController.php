<?php namespace Agency\Cms\Controllers;

use Agency\Media\Temp\Contracts\TemporaryInterface;

use Input, Response,File;

class TempsController extends \Controller {

    public function __construct(TemporaryInterface $temp)
    {
        $this->temp = $temp;
    }
	

    public function storePhotos()
    {
        $input = Input::all();
        $images = $input["images"];
        $tempImages = [];
        foreach ($images as $key => $image) {
           array_push($tempImages, $this->temp->storeImage($image));
        }

        return json_encode($tempImages);
    }

    /**
     * delete temporary image from the disk
     * @return boolean
     */
    public function deletePhoto()
    {


        try {
            $result = File::delete(public_path()."/tmp/".Input::get('image'));

            return Response::json(["result"=>$result]);
            

        } catch (Exception $e) {
            
            return Response::json(['message'=>$e->getMessage()]);
        }
    }
}
