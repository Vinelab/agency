<?php namespace Agency\Tests\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use TestCase, Mockery as M;

use AblaFahita\Repositories\ImageRepository;

class ImageRepositoryTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mImage = M::mock('AblaFahita\Contracts\ImageInterface');
        $this->mHelper = M::Mock('AblaFahita\Contracts\HelperInterface');
        $this->images = new ImageRepository($this->mImage,$this->mHelper);
        $this->mHelper->shouldReceive('getUniqueId')->andReturn('12345');
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function test_bindings()
    {
        $image = $this->app->make('AblaFahita\Contracts\ImageInterface');
        $this->assertInstanceOf('AblaFahita\Image', $image);

        $images = $this->app->make('AblaFahita\Contracts\Repositories\ImageRepositoryInterface');
        $this->assertInstanceOf('AblaFahita\Repositories\ImageRepository', $images);
    }

    public function test_creating_image()
    {
        $this->mImage->shouldReceive('presetType')->with('original')->andReturn('original');
        $this->mImage->shouldReceive('presetType')->with('thumbnail')->andReturn('thumbnail');
        $this->mImage->shouldReceive('presetType')->with('square')->andReturn('square');
        $this->mImage->shouldReceive('presetType')->with('small')->andReturn('small');


        $original  = M::mock('AblaFahita\Media\Photos\Photo');
        $original->url = 'http://placekitten.com/1024/768';

        $thumbnail = M::mock('AblaFahita\Media\Photos\Photo');
        $thumbnail->url = 'http://placekitten.com/300/200';

        $small = M::mock('AblaFahita\Media\Photos\Photo');
        $small->url = 'http://pacekitten.com/320/128';

        $square = M::mock('AblaFahita\Media\Photos\Photo');
        $square->url = 'http://plackitten.com/200/200';


        $this->mImage->shouldReceive('create')->with(M::subset([
            'original' => $original->url,
            'thumbnail' => $thumbnail->url,
            'small' => $small->url,
            'square'=>$square->url
        ]))->andReturn($this->mImage);


        $original_image = $this->images->create($original, $thumbnail, $small, $square);
        $this->assertInstanceOf('AblaFahita\Contracts\ImageInterface', $original_image);
    }

    public function test_getting_thumbnail()
    {
        $guid = uniqid();

        $this->mImage->shouldReceive('presetType')->with('thumbnail')->andReturn('thumbnail');
        $this->mImage->shouldReceive('where')->with('guid', '=', $guid)->andReturn($this->mImage);
        $this->mImage->shouldReceive('where')->with('preset', '=', 'thumbnail')->andReturn($this->mImage);
        $this->mImage->shouldReceive('first')->andReturn($this->mImage);

        $this->assertInstanceOf('AblaFahita\Contracts\ImageInterface', $this->images->getThumbnail($guid));
    }

    public function test_store()
    {
        $mPhoto = M::mock('AblaFahita\Media\Photos\Photo');



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


        $this->mImage->shouldReceive('insert')->with($photos_without_original)
             ->andReturn(true);



        $original_photo = $this->images->store($photos_without_original);

        $this->assertTrue($original_photo);

    }


        public function test_getByGuid()
    {
        $guid = 'guid';
        $mCollection = M::mock('Illuminate\Database\Eloquent\Collection');
        $this->mImage->shouldReceive('where')->with('guid','=',$guid)->andReturn($this->mImage)
            ->shouldReceive('get')->andReturn($mCollection);

        $image = $this->images->getByGuid($guid);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',$image);
    }

    public function test_remove_with_valid_ids()
    {
        $this->mImage->shouldReceive('destroy')->with([1,2,3])->andReturn(3);

        $deleted_images = $this->images->remove([1,2,3]);

        $this->assertEquals($deleted_images,3);
    }

    public function test_remove_with_invalid_ids()
    {
        $this->mImage->shouldReceive('destroy')->with([2])->andReturn(0);
        $deleted_images = $this->images->remove([2]);

        $this->assertEquals($deleted_images,0);
    }




}
