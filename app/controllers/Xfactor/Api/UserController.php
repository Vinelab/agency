<?php namespace Xfactor\Api\Controllers;

use Xfactor\Exceptions\InvalidUserException;
use Xfactor\Contracts\Validators\UserValidatorInterface;
use Xfactor\Contracts\Repositories\UserRepositoryInterface;
use Xfactor\Contracts\Repositories\ScoreRepositoryInterface;
use Controller, Input, Response, Exception;
use Api;
use Xfactor\Contracts\Services\ScoreServiceInterface;

class UserController extends Controller {

	public function __construct(UserValidatorInterface $validator,
								UserRepositoryInterface $users,
								ScoreRepositoryInterface $score_repo,
								ScoreServiceInterface $score_service)
	{
		$this->validator = $validator;
		$this->users = $users;
		$this->score_repo = $score_repo;
		$this->score_service = $score_service;
	}


	public function show($id)
	{
		$user = $this->getUser($id);
		return Api::respond('UserMapper', $user);

	}

	public function score($id)
	{
		$user = $this->getUser($id);

		$score = $this->score_service->getScore($id);
		$total = array_sum($score);
		$rank = $this->score_service->getUserRank($id, $user->team->slug);

		$result = [
			'rank' => $rank,
			'total' => $total,
			'points' =>$score
		];
		
		return Api::respond('ScoreMapper', $result);

	}

	public function updateScore($id)
	{
		$user = $this->getUser($id);

		$this->score_repo->update(	$user->score->id,
									Input::get('sharing'),
									Input::get('commenting'),
									Input::get('chatting'),
									Input::get('others')
								);

		$this->score_service->updateScore($id, Input::all(), $user->team->slug());

		return $this->score($id);

	}


	public function getUser($id)
	{
		return $this->users->findBy('gigya_id',$id);
	}




}