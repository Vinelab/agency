<?php namespace Xfactor\Api\Controllers;

use Xfactor\Exceptions\InvalidTeamException;
use Xfactor\Contracts\Validators\TeamValidatorInterface;
use Xfactor\Contracts\Repositories\TeamRepositoryInterface;
use Controller, Input, Response, Exception;
use Api;
use Xfactor\Contracts\Services\UsersServiceInterface;
use Xfactor\Contracts\Services\ScoreServiceInterface;
use Xfactor\Contracts\Repositories\UserRepositoryInterface;

class TeamController extends Controller {

	public function __construct(TeamValidatorInterface $validator,
								TeamRepositoryInterface $teams,
								UsersServiceInterface $users_service,
								ScoreServiceInterface $score_service,
								UserRepositoryInterface $users)
	{
		$this->validator = $validator;
		$this->teams = $teams;
		$this->users_service = $users_service;
		$this->score_service = $score_service;
		$this->users = $users;
		
	}

	public function index()
	{
		$teams = $this->teams->all();

		return Api::respond('TeamMapper', $teams);
	}

	public function show($idOrSlug)
	{
		$team = $this->teams->findByIdOrSlug($idOrSlug);

		return Api::respond('TeamMapper', $team);
	}

	public function join($idOrSlug)
	{
		
		try {

			$user = $this->users_service->create();
			$this->teams->join($idOrSlug, $user);
			$this->score_service->createScore($user->gigyaId(), $idOrSlug);

			return Api::respond('UserMapper', $user);


		} catch (InvalidUserException $e) {
				return Api::error($e, $e->getCode(), 401);		
		}

	}

	public function members($idOrSlug)
	{
		$users = $this->teams->members($idOrSlug);
		return Api::respond('UserMapper',$users);
	}

	public function score($idOrSlug)
	{
		$score = $this->score_service->getTeamScore($idOrSlug);
		$total = array_sum($score);
		$rank = $this->score_service->getTeamRank($idOrSlug);
		$members_count = $this->score_service->getTeamMembersCount($idOrSlug);

		$result = [
			'rank' => $rank,
			'total' => $total,
			'points' =>$score,
			'members_count' => $members_count
		];
		
		return Api::respond('ScoreMapper', $result);

	}

	public function leaderboard($idOrSlug)
	{
		
		$ranks_count = !is_null(Input::get('ranks_count'))? Input::get('ranks_count') : 20;
		$users_ranking = [];
		$user_rank = null;
		
		if(!is_null(Input::get('user_id')))
		{
			$user_id = Input::get('user_id');
			$user_rank = $this->score_service->getUserRank($user_id, $idOrSlug);
			$ranking = !is_null(Input::get('user_ranking'))? Input::get('user_ranking') : 5;
			if($user_rank > $ranks_count)
			{

				$base = $user_rank - $ranking;
				$users_ranking = $this->getRanks($idOrSlug, $base, $ranking*2+1);

			} 

		}

		$result = [
			'ranks' => $this->getRanks($idOrSlug, 0, $ranks_count) ,
			'user_ranking' => $users_ranking,
			'position' => $user_rank
		];



		return Api::respond('LeaderboardMapper', $result);

	}


	public function formatScore($points, $idOrSlug)
	{

		$scores = [];

		foreach ($points as $id => $score) {
			$total = array_sum($score);
			$rank = $this->score_service->getUserRank($id, $idOrSlug);

			$result = [
				'rank' => $rank,
				'total' => $total,
				'points' => $score
			];

			$scores[$id] = $result;

		}

		return $scores;
	}


	public function appendScoreToCollection($users, $scores)
	{
		foreach ($users as $key => $user) {
			$user->score = (object)$scores[$user->gigya_id];
		}

		return $users;

	}

	public function getRanks($idOrSlug, $base, $count)
	{
		$leaderboard = $this->score_service->getTeamSortedMembers($idOrSlug,$base,$count);

		$ids = array_keys($leaderboard);

		$points = $this->score_service->getMultipleScore($ids);

		$scores = $this->formatScore($points, $idOrSlug);

		$users = $this->users->get($ids);

		$users = $users->getCollection();

		$users = $this->appendScoreToCollection($users, $scores);

		$users = $users->sortByDesc(function($user){
			return $user->score->total;
		});

		$users = $users->toArray();
		$users = array_values($users);

		return $users;

	}




	



}