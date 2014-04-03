<?php namespace Agency\Media\Photos;

use Agency\Media\Photos\Contracts\FilterResponseInterface;
use Agency\Image;
use Agency\Contracts\HelperInterface;

class FilterResponse implements FilterResponseInterface {

    protected $helper;

    public function __construct(HelperInterface $helper)
    {
        $this->helper = $helper;
    }

    public function make($response)
    {
        $without_original = [];
        $originals = [];
        foreach ($response as $image) {

            $unique_id = $this->helper->getUniqueId();

            array_push($without_original,[
                'url' => $image['thumbnail']->url,
                'preset' => 'thumbnail',
                'guid' => $unique_id,
            ]);

            array_push($without_original,[
                'url' => $image['small']->url,
                'preset' => 'small',
                'guid' => $unique_id,
            ]);

            array_push($without_original,[
                'url' => $image['square']->url,
                'preset' => 'square',
                'guid' => $unique_id,
            ]);

            array_push($originals, new Image([
                'url' => $image['original']->url,
                'preset' => 'original',
                'guid' => $unique_id,
            ]));
        }

        return compact('without_original','originals');
    }
}