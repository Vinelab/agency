<?php namespace Xfactor\Tests\Unit;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use TestCase, Mockery as M;
use Illuminate\Redis\Database as Redis;
use App;

class ScoreServiceTest extends TestCase {



	public function setUp()
	{

		parent::setUp();
		$this->score_service = App::make('Xfactor\Services\ScoreService');
		$this->redis = App::make('Illuminate\Redis\Database');


	}

	public function tearDown()
	{
		$this->redis->flushAll();
		parent::tearDown();
	}

	public function testCreatingScore()
	{
		$id = 'bob';
		$team = 'elissa';

		$score = $this->score_service->createScore($id, $team);
		$this->assertTrue($score);
		$score = $this->score_service->getScore($id, $team);
		$this->assertArrayHasKey('chatting', $score);
		$this->assertEquals(0,$score['chatting']);
		$this->assertArrayHasKey('commenting', $score);
		$this->assertEquals(0,$score['commenting']);
		$this->assertArrayHasKey('sharing', $score);
		$this->assertEquals(0,$score['sharing']);
		$this->assertArrayHasKey('others', $score);
		$this->assertEquals(0,$score['others']);


	}


	public function testCreateTeamScore()
	{
		$team = 'elissa';

		$score = $this->score_service->createTeamScore($team);
		$this->assertTrue($score);
		$score = $this->score_service->getTeamScore($team);
		$this->assertArrayHasKey('chatting', $score);
		$this->assertEquals(0,$score['chatting']);
		$this->assertArrayHasKey('commenting', $score);
		$this->assertEquals(0,$score['commenting']);
		$this->assertArrayHasKey('sharing', $score);
		$this->assertEquals(0,$score['sharing']);
		$this->assertArrayHasKey('others', $score);
		$this->assertEquals(0,$score['others']);
	}

	public function testUpdatingTeamScore()
	{
		$team = 'elissa';
		$score = $this->score_service->createTeamScore($team);

		$score = [
			'chatting' => 100,
            'sharing' => 200,
            'commenting' => 300,
            'others' => 400
		];


		$score = $this->score_service->updateTeamScore($team, $score);
		
		$this->assertTrue($score);
		$score = $this->score_service->getTeamScore($team);
		$this->assertArrayHasKey('chatting', $score);
		$this->assertEquals(100,$score['chatting']);
		$this->assertArrayHasKey('commenting', $score);
		$this->assertEquals(300,$score['commenting']);
		$this->assertArrayHasKey('sharing', $score);
		$this->assertEquals(200,$score['sharing']);
		$this->assertArrayHasKey('others', $score);
		$this->assertEquals(400,$score['others']);
	}



	public function testUpdatingScore()
	{

		$id = 'bob';
		$team = 'elissa';

		$old_score = $this->score_service->createScore($id, $team);

		$score = [
			'chatting' => 10,
            'sharing' => 20,
            'commenting' => 30,
            'others' => 40
		];

		$score = $this->score_service->updateScore($id, $score, $team);

		$this->assertTrue($score);
		$score = $this->score_service->getScore($id, $team);
		$this->assertArrayHasKey('chatting', $score);
		$this->assertEquals(10,$score['chatting']);
		$this->assertArrayHasKey('commenting', $score);
		$this->assertEquals(30,$score['commenting']);
		$this->assertArrayHasKey('sharing', $score);
		$this->assertEquals(20,$score['sharing']);
		$this->assertArrayHasKey('others', $score);
		$this->assertEquals(40,$score['others']);


	}

	public function testGettingTeamSortedMembers()
	{
		$team = 'elissa';
		$this->createMultipleUsers();
		$result = $this->score_service->getTeamSortedMembers($team);
		$keys =array_keys($result);

		$this->assertEquals('abed', $keys[0]);
		$this->assertEquals('150', $result['abed']);
		$this->assertEquals('nabil', $keys[1]);
		$this->assertEquals('130', $result['nabil']);
		$this->assertEquals('zack', $keys[2]);
		$this->assertEquals('90', $result['zack']);

	}


	public function testGettingTeams()
	{
		$this->createMultipleUsers();
		$teams = $this->score_service->getTeams();
		$this->assertEquals(3, sizeof($teams));

		$this->assertEquals('donia:total', $teams[0][0]);
		$this->assertEquals('730', $teams[0][1]);

		$this->assertEquals('elissa:total', $teams[1][0]);
		$this->assertEquals('675', $teams[1][1]);

		$this->assertEquals('ragheb:total', $teams[2][0]);
		$this->assertEquals('441', $teams[2][1]);

	}

	public function testGettingUserRank()
	{
		$this->createMultipleUsers();

		$team = 'elissa';
		$id = 'tony';
		$rank = $this->score_service->getUserRank($id, $team);
		$this->assertEquals('5',$rank);

	}

	public function testGettingScore()
	{
		$id = 'bob';
		$team = 'elissa';

		$old_score = $this->score_service->createScore($id, $team);

		$score = [
			'chatting' => 10,
            'sharing' => 20,
            'commenting' => 30,
            'others' => 40
		];

		$score = $this->score_service->updateScore($id, $score, $team);

		$this->assertTrue($score);
		$score = $this->score_service->getScore($id, $team);
		$this->assertArrayHasKey('chatting', $score);
		$this->assertEquals(10,$score['chatting']);
		$this->assertArrayHasKey('commenting', $score);
		$this->assertEquals(30,$score['commenting']);
		$this->assertArrayHasKey('sharing', $score);
		$this->assertEquals(20,$score['sharing']);
		$this->assertArrayHasKey('others', $score);
		$this->assertEquals(40,$score['others']);

	}

	public function testGettingTeamScore()
	{
		$team = 'elissa';
		$score = $this->score_service->createTeamScore($team);

		$score = [
			'chatting' => 100,
            'sharing' => 200,
            'commenting' => 300,
            'others' => 400
		];


		$score = $this->score_service->updateTeamScore($team, $score);
		
		$this->assertTrue($score);
		$score = $this->score_service->getTeamScore($team);
		$this->assertArrayHasKey('chatting', $score);
		$this->assertEquals(100,$score['chatting']);
		$this->assertArrayHasKey('commenting', $score);
		$this->assertEquals(300,$score['commenting']);
		$this->assertArrayHasKey('sharing', $score);
		$this->assertEquals(200,$score['sharing']);
		$this->assertArrayHasKey('others', $score);
		$this->assertEquals(400,$score['others']);	
	}

	public function testGettingTeamRank()
	{
		$this->createMultipleUsers();
		$team = 'elissa';
		$rank = $this->score_service->getTeamRank($team);
		$this->assertEquals('1', $rank);

	}

	public function testGettingMembersCount()
	{
		$team = 'elissa';
		$this->createMultipleUsers();

		$count = $this->score_service->getTeamMembersCount($team);
		$this->assertEquals('7', $count);
		
	}










	

	// public function testGettingTeamSortedMembers()
	// {
	// 	$result = $this->score_service->getTeamSortedMembers('ragheb',0,2);
	// 	return $this->assertTrue(true);
	// }

	// public function testGettingUserRank()
	// {
	// 	$result = $this->score_service->getUserRank('raed','ragheb');
	// 	return $this->assertTrue(true);
	// }

	// public function testGettingScore()
	// {
	// 	$result = $this->score_service->getScore('zack');
	// 	return $this->assertTrue(true);
	// }

	// public function testGettingTeamScore()
	// {
	// 	$result = $this->score_service->getTeamScore('elissa');
	// 	return $this->assertTrue(true);
	// }

	// public function testGettingTeams()
	// {
	// 	$result = $this->score_service->getTeams();
	// 	return $this->assertTrue;
	// }


	public function createMultipleUsers()
	{

		$this->score_service->createTeamScore('elissa');
		$this->score_service->createTeamScore('ragheb');
		$this->score_service->createTeamScore('donia');

		$scores = [

			'zack' => [
				'chatting' => 0,
	            'sharing' => 20,
	            'commenting' => 30,
	            'others' => 40
			],

			'jad' => [
				'chatting' => 0,
	            'sharing' => 20,
	            'commenting' => 30,
	            'others' => 40
			],

			'nabil' => [
				'chatting' => 10,
	            'sharing' => 50,
	            'commenting' => 30,
	            'others' => 40
			],
			'wassim' => [
				'chatting' => 10,
	            'sharing' => 20,
	            'commenting' => 30,
	            'others' => 0
			],
			'rasha' => [
				'chatting' => 10,
	            'sharing' => 5,
	            'commenting' => 30,
	            'others' => 40
			],
			'tony' => [
				'chatting' => 15,
	            'sharing' => 20,
	            'commenting' => 30,
	            'others' => 5
			],
			'abed' => [
				'chatting' => 10,
	            'sharing' => 80,
	            'commenting' => 30,
	            'others' => 30
			]
		];


		foreach ($scores as $key => $value) {
			$this->score_service->createScore($key, 'elissa');
			$this->score_service->updateScore($key,$value, 'elissa');
		}


		$scores = [

			'nancy' => [
				'chatting' => 0,
	            'sharing' => 10,
	            'commenting' => 30,
	            'others' => 40
			],

			'amal' => [
				'chatting' => 0,
	            'sharing' => 40,
	            'commenting' => 30,
	            'others' => 40
			],

			'hiyam' => [
				'chatting' => 30,
	            'sharing' => 50,
	            'commenting' => 30,
	            'others' => 40
			],
			'ghofran' => [
				'chatting' => 10,
	            'sharing' => 25,
	            'commenting' => 30,
	            'others' => 0
			],
			'fat7a' => [
				'chatting' => 10,
	            'sharing' => 55,
	            'commenting' => 30,
	            'others' => 40
			],
			'chrinneh' => [
				'chatting' => 15,
	            'sharing' => 0,
	            'commenting' => 30,
	            'others' => 5
			],
			'habla' => [
				'chatting' => 10,
	            'sharing' => 100,
	            'commenting' => 30,
	            'others' => 0
			]
		];

		foreach ($scores as $key => $value) {

			$this->score_service->createScore($key, 'donia');
			$this->score_service->updateScore($key,$value, 'donia');
		}


		$scores = [

			'amir' => [
				'chatting' => 0,
	            'sharing' => 5,
	            'commenting' => 30,
	            'others' => 10
			],

			'abbas' => [
				'chatting' => 20,
	            'sharing' => 15,
	            'commenting' => 10,
	            'others' => 5
			],

			'raed' => [
				'chatting' => 1,
	            'sharing' => 20,
	            'commenting' => 30,
	            'others' => 0
			],
			'batti5' => [
				'chatting' => 0,
	            'sharing' => 50,
	            'commenting' => 60,
	            'others' => 10
			],
			'hassan' => [
				'chatting' => 70,
	            'sharing' => 10,
	            'commenting' => 20,
	            'others' => 5
			],
			'elias' => [
				'chatting' => 0,
	            'sharing' => 0,
	            'commenting' => 10,
	            'others' => 5
			],
			'ibrahim' => [
				'chatting' => 0,
	            'sharing' => 40,
	            'commenting' => 10,
	            'others' => 5
			]
		];

		foreach ($scores as $key => $value) {

			$this->score_service->createScore($key, 'ragheb');
			$this->score_service->updateScore($key,$value, 'ragheb');
		}



		return $this->assertTrue(true);
	}


	



}
