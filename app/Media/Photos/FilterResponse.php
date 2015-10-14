<?php namespace Agency\Media\Photos;

use Agency\Media\Photos\Contracts\FilterResponseInterface;
use Agency\Image;
use Agency\Contracts\HelperInterface;

class FilterResponse implements FilterResponseInterface {

    public function make($response)
    {
        $originals = [];
        foreach ($response as $image) {
            array_push($originals, new Image([
                'original' => $image['original']->url,
                'thumbnail'=> $image['thumbnail']->url,
                'small' => $image['small']->url,
                'square' => $image['square']->url
            ]));
        }

        return ['originals' => $originals];
    }
}
