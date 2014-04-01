<?php namespace Agency\Tests\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;

use Agency\Repositories\ImageRepository;

class ImageRepositoryTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mImage = M::mock('Agency\Contracts\ImageInterface');
        $this->images = new ImageRepository($this->mImage);
    }

    public function test_bindings()
    {
        $image = $this->app->make('Agency\Contracts\ImageInterface');
        $this->assertInstanceOf('Agency\Image', $image);

        $images = $this->app->make('Agency\Repositories\Contracts\ImageRepositoryInterface');
        $this->assertInstanceOf('Agency\Repositories\ImageRepository', $images);
    }

    public function test_creating_image()
    {
        $this->mImage->shouldReceive('presetType')->with('original')->once()->andReturn('original');
        $this->mImage->shouldReceive('presetType')->with('thumbnail')->once()->andReturn('thumbnail');
        $this->mImage->shouldReceive('presetType')->with('square')->once()->andReturn('square');
        $this->mImage->shouldReceive('presetType')->with('small')->once()->andReturn('small');


        $original  = M::mock('Agency\Media\Photos\Photo');
        $original->url = 'http://placekitten.com/1024/768';

        $this->mImage->shouldReceive('create')->once()->with(M::subset([
            'url' => $original->url,
            'preset' => 'original'
        ]))->andReturn($this->mImage);

        $thumbnail = M::mock('Agency\Media\Photos\Photo');
        $thumbnail->url = 'http://placekitten.com/300/200';
        $this->mImage->shouldReceive('create')->once()->with(M::subset([
            'url' => $thumbnail->url,
            'preset' => 'thumbnail'
        ]));

        $small = M::mock('Agency\Media\Photos\Photo');
        $small->url = 'http://pacekitten.com/320/128';
        $this->mImage->shouldReceive('create')->once()->with(M::subset([
            'url' => $small->url,
            'preset' => 'small'
        ]));

        $square = M::mock('Agency\Media\Photos\Photo');
        $square->url = 'http://plackitten.com/200/200';
        $this->mImage->shouldReceive('create')->once()->with(M::subset([
            'url' => $square->url,
            'preset' => 'square'
        ]));

        $original_image = $this->images->create($original, $thumbnail, $small, $square);
        $this->assertInstanceOf('Agency\Contracts\ImageInterface', $original_image);
    }

    public function test_getting_thumbnail()
    {
        $guid = uniqid();

        $this->mImage->shouldReceive('presetType')->once()->with('thumbnail')->andReturn('thumbnail');
        $this->mImage->shouldReceive('where')->once()->with('guid', '=', $guid)->andReturn($this->mImage);
        $this->mImage->shouldReceive('where')->once()->with('preset', '=', 'thumbnail')->andReturn($this->mImage);
        $this->mImage->shouldReceive('first')->once()->andReturn($this->mImage);

        $this->assertInstanceOf('Agency\Contracts\ImageInterface', $this->images->getThumbnail($guid));
    }

    public function test_store()
    {
        $mPhoto = M::mock('Agency\Media\Photos\Photo');

        

        $photos_without_original =[];

        array_push($photos_without_original,[
                'url' => "https://s3.amazonaws.com/awsfacebookapp%2Fartists%2Fwebs/53398439beb34.thumb.jpeg",
                'preset' => 'thumbnail',
                'guid' => "uniqueid"

            ]);

        array_push($photos_without_original,[
                'url' => "https://s3.amazonaws.com/awsfacebookapp%2Fartists%2Fwebs/53398439beb34.thumb.jpeg",
                'preset' => 'small',
                'guid' => "uniqueid"
            ]);
        
        array_push($photos_without_original,[
                'url' => "https://s3.amazonaws.com/awsfacebookapp%2Fartists%2Fwebs/53398439beb34.thumb.jpeg",
                'preset' => 'square',
                'guid' => "uniqueid"
            ]);


        $this->mImage->shouldReceive('insert')->with($photos_without_original)->once()
             ->andReturn(true);


        
        $original_photo = $this->images->store($photos_without_original);

        $this->assertTrue($original_photo);

    }




}
