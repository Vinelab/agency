<?php namespace Xfactor\Tests\Integration;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use TestCase, Mockery as M;

use App;
use URL;

class TeamTest extends TestCase {


	public function setUp()
	{

		parent::setUp();
		$this->redis = App::make('Illuminate\Redis\Database');
		require app_path() . '/launch/' . "api.routes.php";
		$this->seed();

	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testGettingTeams()
	{
		$response = $this->call('GET', URL::route('api.teams.index'));
		$response = $response->getContent();
		$teams = json_decode($response);

		$this->assertObjectHasAttribute('data', $teams);
		$this->assertEquals('3', sizeof($teams->data));

		$this->assertObjectHasAttribute('title', $teams->data[0]);
		$this->assertEquals('اليسا', $teams->data[0]->title);

		$this->assertObjectHasAttribute('slug', $teams->data[0]);
		$this->assertEquals('اليسا', $teams->data[0]->slug);

		$this->assertObjectHasAttribute('image', $teams->data[0]);
		$this->assertObjectHasAttribute('original', $teams->data[0]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.jpeg', $teams->data[0]->image->original);

		$this->assertObjectHasAttribute('thumbnail', $teams->data[0]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.thumb.jpeg', $teams->data[0]->image->thumbnail);

		$this->assertObjectHasAttribute('square', $teams->data[0]->image);

		$this->assertObjectHasAttribute('small', $teams->data[0]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.small.jpeg', $teams->data[0]->image->small);

		$this->assertObjectHasAttribute('members_count', $teams->data[0]);
		$this->assertEquals('11', $teams->data[0]->members_count);



		$this->assertObjectHasAttribute('title', $teams->data[1]);
		$this->assertEquals('راغب علامة', $teams->data[1]->title);

		$this->assertObjectHasAttribute('slug', $teams->data[1]);
		$this->assertEquals('راغب-علامة', $teams->data[1]->slug);

		$this->assertObjectHasAttribute('image', $teams->data[1]);
		$this->assertObjectHasAttribute('original', $teams->data[1]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e373b13d236.jpeg', $teams->data[1]->image->original);

		$this->assertObjectHasAttribute('thumbnail', $teams->data[1]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e373b13d236.thumb.jpeg', $teams->data[1]->image->thumbnail);

		$this->assertObjectHasAttribute('square', $teams->data[1]->image);

		$this->assertObjectHasAttribute('small', $teams->data[1]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e373b13d236.small.jpeg', $teams->data[1]->image->small);

		$this->assertObjectHasAttribute('members_count', $teams->data[1]);
		$this->assertEquals('10', $teams->data[1]->members_count);


		$this->assertObjectHasAttribute('title', $teams->data[2]);
		$this->assertEquals('دنيا', $teams->data[2]->title);

		$this->assertObjectHasAttribute('slug', $teams->data[2]);
		$this->assertEquals('دنيا', $teams->data[2]->slug);

		$this->assertObjectHasAttribute('image', $teams->data[2]);
		$this->assertObjectHasAttribute('original', $teams->data[2]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e3741d9df48.jpeg', $teams->data[2]->image->original);

		$this->assertObjectHasAttribute('thumbnail', $teams->data[2]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e3741d9df48.thumb.jpeg', $teams->data[2]->image->thumbnail);

		$this->assertObjectHasAttribute('square', $teams->data[2]->image);

		$this->assertObjectHasAttribute('small', $teams->data[2]->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e3741d9df48.small.jpeg', $teams->data[2]->image->small);

		$this->assertObjectHasAttribute('members_count', $teams->data[2]);
		$this->assertEquals('6', $teams->data[2]->members_count);

	}

	public function testGettingSpecificTeam()
	{
		$team = 'اليسا';

		$response = $this->call('GET', URL::route('api.teams.show',[$team]));
		$response = $response->getContent();
		$team = json_decode($response);

		$this->assertObjectHasAttribute('data', $team);

		$this->assertObjectHasAttribute('title', $team->data);
		$this->assertEquals('اليسا', $team->data->title);

		$this->assertObjectHasAttribute('slug', $team->data);
		$this->assertEquals('اليسا', $team->data->slug);

		$this->assertObjectHasAttribute('image', $team->data);
		$this->assertObjectHasAttribute('original', $team->data->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.jpeg', $team->data->image->original);

		$this->assertObjectHasAttribute('thumbnail', $team->data->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.thumb.jpeg', $team->data->image->thumbnail);

		$this->assertObjectHasAttribute('square', $team->data->image);

		$this->assertObjectHasAttribute('small', $team->data->image);
		$this->assertEquals('https://s3.amazonaws.com/awsfacebookapp/artists/webs/54e36a6654c06.small.jpeg', $team->data->image->small);

		$this->assertObjectHasAttribute('members_count', $team->data);
		$this->assertEquals('11', $team->data->members_count);
	}

	public function testJoiningaTeam()
	{
		$team = 'اليسا';



		$response = $this->call('POST', URL::route('api.teams.join',[$team]),[
			"name"=> "test user",
			"gigya_id"=> "_guid_test",
			"avatar"=> "https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-xpa1/v/t1.0-9/10857827_10152439008631993_3007437159977895539_n.jpg?oh=5979ca7f5b6bd9107e733a1b67a5d2c9&oe=5554A8EA&__gda__=1432055036_d702a7b58077e850ee2d3732e0b3c594",
			"country"=> "lebanon"
		]);
		$response = $response->getContent();
		$user = json_decode($response);


		$this->assertObjectHasAttribute('data', $user);
		$this->assertObjectHasAttribute('id', $user->data);

		$this->assertObjectHasAttribute('name', $user->data);
		$this->assertEquals('test user', $user->data->name);

		$this->assertObjectHasAttribute('gigya_id', $user->data);
		$this->assertEquals('_guid_test', $user->data->gigya_id);
		
		$this->assertObjectHasAttribute('avatar', $user->data);
		$this->assertEquals('https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-xpa1/v/t1.0-9/10857827_10152439008631993_3007437159977895539_n.jpg?oh=5979ca7f5b6bd9107e733a1b67a5d2c9&oe=5554A8EA&__gda__=1432055036_d702a7b58077e850ee2d3732e0b3c594', $user->data->avatar);

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
		$this->assertEquals('12', $user->data->team->members_count);

	}

	public function testGettingTeamScore()
	{
		$team = 'اليسا';

		$response = $this->call('GET', URL::route('api.teams.score',[$team]));
		$response = $response->getContent();
		$score = json_decode($response);

		$this->assertObjectHasAttribute('data', $score);
		$this->assertObjectHasAttribute('rank', $score->data);
		$this->assertEquals('2', $score->data->rank);
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

		$this->assertEquals('12', $score->data->members_count);

	}


	public function testGettingLeaderboard()
	{
		$team = 'اليسا';

		$response = $this->call('GET', URL::route('api.teams.leaderboard',[$team]));
		$response = $response->getContent();
		$score = json_decode($response);

		$this->assertObjectHasAttribute('data', $score);
		$this->assertObjectHasAttribute('ranks', $score->data);
		$this->assertObjectHasAttribute('user_ranking', $score->data);
		$this->assertObjectHasAttribute('position', $score->data);

	}








}
