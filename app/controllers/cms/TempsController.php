<?php namespace Agency\Cms\Controllers;

use Agency\Media\Temp\Contracts\TemporaryInterface;

use Input, Response;

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
}
