<?php namespace Xfactor\Tests\Integration;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use TestCase, Mockery as M;

use Agency\Repositories\UserRepository;
use App;
use URL;

class UserTest extends TestCase {


	public function setUp()
	{

		parent::setUp();
		$this->redis = App::make('Illuminate\Redis\Database');
		require app_path() . '/launch/' . "api.routes.php";
		$this->seed();
		// $seeder = App::make('DatabaseSeeder');
		// $seeder->run();


	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testGettingUser()
	{
		$response = $this->call('GET', URL::route('api.users.show',['_guid_gigya_id_5']));
		$response = $response->getContent();
		$user = json_decode($response);

		$this->assertObjectHasAttribute('data', $user);
		$this->assertObjectHasAttribute('id', $user->data);

		$this->assertObjectHasAttribute('name', $user->data);
		$this->assertEquals('Rasha Kassab', $user->data->name);

		$this->assertObjectHasAttribute('gigya_id', $user->data);
		$this->assertEquals('_guid_gigya_id_5', $user->data->gigya_id);
		
		$this->assertObjectHasAttribute('avatar', $user->data);
		$this->assertEquals('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfp1/v/t1.0-1/p160x160/10411204_959528017272_5614593003384879480_n.jpg?oh=c2e1dd9f96635476ef0dfd6afe6eab09&oe=556128CF&__gda__=1432501978_a301cbcab7339047d7020cb6dc9068ff', $user->data->avatar);

		$this->assertObjectHasAttribute('country', $user->data);
		$this->assertEquals('lebanon', $user->data->country);

		$this->assertObjectHasAttribute('team', $user->data);
		$this->assertObjectHasAttribute('id', $user->data->team);

		$this->assertObjectHasAttribute('title', $user->data->team);
		$this->assertEquals('اليسا', $user->data->team->title);

		$this->assertObjectHasAttribute('slug', $user->data->team);
		$this->assertEquals('اليسا', $user->data->team->slug);

		$this->assertObjectHasAttribute('image', $user->data->team);
		$this->assertObjectHasAttribute('original', $user->data->team->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.jpeg', $user->data->team->image->original);

		$this->assertObjectHasAttribute('thumbnail', $user->data->team->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.thumb.jpeg', $user->data->team->image->thumbnail);

		$this->assertObjectHasAttribute('square', $user->data->team->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.square.jpeg', $user->data->team->image->square);

		$this->assertObjectHasAttribute('small', $user->data->team->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.small.jpeg', $user->data->team->image->small);

		$this->assertObjectHasAttribute('members_count', $user->data->team);
		$this->assertEquals('11', $user->data->team->members_count);

	}


	public function testGettingUserScore()
	{


		$response = $this->call('GET', URL::route('api.users.score',['_guid_gigya_id_5']));
		$response = $response->getContent();
		$score = json_decode($response);

		$this->assertObjectHasAttribute('data', $score);
		$this->assertObjectHasAttribute('rank', $score->data);
		$this->assertEquals('4', $score->data->rank);
		$this->assertEquals('0', $score->data->total);
		$this->assertObjectHasAttribute('points', $score->data);

		$this->assertObjectHasAttribute('chatting', $score->data->points);
		$this->assertEquals('0', $score->data->points->chatting);

		$this->assertObjectHasAttribute('sharing', $score->data->points);
		$this->assertEquals('0', $score->data->points->sharing);

		$this->assertObjectHasAttribute('commenting', $score->data->points);
		$this->assertEquals('0', $score->data->points->commenting);

		$this->assertObjectHasAttribute('others', $score->data->points);
		$this->assertEquals('0', $score->data->points->others);

	}

	public function testUpdatingScore()
	{
		$response = $this->call('POST', URL::route('api.users.score',['_guid_gigya_id_5']), [
			'sharing' => 100,
			'chatting' => 200,
			'commenting' => 100,
			'others' => 50
		]);
		$response = $response->getContent();
		$score = json_decode($response);



		$this->assertObjectHasAttribute('data', $score);
		$this->assertObjectHasAttribute('rank', $score->data);
		$this->assertEquals('0', $score->data->rank);
		$this->assertEquals('450', $score->data->total);
		$this->assertObjectHasAttribute('points', $score->data);

		$this->assertObjectHasAttribute('chatting', $score->data->points);
		$this->assertEquals('200', $score->data->points->chatting);

		$this->assertObjectHasAttribute('sharing', $score->data->points);
		$this->assertEquals('100', $score->data->points->sharing);

		$this->assertObjectHasAttribute('commenting', $score->data->points);
		$this->assertEquals('100', $score->data->points->commenting);

		$this->assertObjectHasAttribute('others', $score->data->points);
		$this->assertEquals('50', $score->data->points->others);
	}

}
