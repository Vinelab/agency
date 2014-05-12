<?php namespace Agency\Tests\Cms\Repositories;

/**
 * @author Ibrahim Fleifel <Ibrahim@vinelab.com>
 */


use TestCase, Mockery as M;

use Agency\Repositories\ImageRepository;


class ImageRepositoryTest extends TestCase {


	public function setUp()
	{
		parent::setUp();
		$this->mImage = M::mock('Agency\Image');
		$this->mHelper = M::mock('Agency\Contracts\HelperInterface');
		$this->mHelper->shouldReceive('getUniqueId')->andReturn('12345');
		$this->image = new ImageRepository($this->mImage,$this->mHelper);
	}

	public function tearDown()
	{
		M::close();

		parent::tearDown();
	}

	public function test_creating()
	{
		$mPhoto = M::mock('Agency\Media\Photos\Photo');
		$mPhoto->url="http://some.url.com";
		$this->mImage->shouldReceive('presetType')->with('original')->andReturn('original');
		$this->mImage->shouldReceive('presetType')->with('thumbnail')->andReturn('thumbnail');
		$this->mImage->shouldReceive('presetType')->with('small')->andReturn('small');
		$this->mImage->shouldReceive('presetType')->with('square')->andReturn('square');

		$this->mImage->shouldReceive('create')->with(M::type('array'))->andReturn($this->mImage);

		$image = $this->image->create($mPhoto,$mPhoto,$mPhoto,$mPhoto);

		$this->assertInstanceOf('Agency\Image', $image);

	}


	public function test_getThumbnail()
	{
		$guid = 'guid_123';
		$this->mImage->shouldReceive('presetType')->with('thumbnail')->andReturn('thumbnail');
		$this->mImage->shouldReceive('where')->with('guid','=',$guid)->andReturn($this->mImage)
			->shouldReceive('where')->with('preset','=','thumbnail')->andReturn($this->mImage);

        $this->mImage->shouldReceive('first')->once()->andReturn($this->mImage);

		$image = $this->image->getThumbnail($guid);

		$this->assertInstanceOf('Agency\Image',$image);
	}

	public function test_getByGuid()
	{
		$guid = 'guid';
		$mCollection = M::mock('Illuminate\Database\Eloquent\Collection');
		$this->mImage->shouldReceive('where')->with('guid','=',$guid)->andReturn($this->mImage)
			->shouldReceive('get')->andReturn($mCollection);

		$image = $this->image->getByGuid($guid);

		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',$image);
	}

	public function test_remove_with_valid_ids()
	{
		$this->mImage->shouldReceive('destroy')->with([1,2,3])->andReturn(3);

		$deleted_images = $this->image->remove([1,2,3]);

		$this->assertEquals($deleted_images,3);
	}

	public function test_remove_with_invalid_ids()
	{
		$this->mImage->shouldReceive('destroy')->with([2])->andReturn(0);
		$deleted_images = $this->image->remove([2]);

		$this->assertEquals($deleted_images,0);
	}

	public function test_store()
	{
		$this->mImage->shouldReceive('insert')->with(M::type('array'))->andReturn(true);
		$images_without_original=['small','thumbnail','square'];
		$image = $this->image->store($images_without_original);

		$this->assertTrue($image);
	}
}