<?php namespace Xfactor\Contracts\Services;

interface ScoreServiceInterface{

	public function getHash($id);

	public function createScore($id, $team);

	public function createTeamScore($id, $team);

	public function updateTeamScore($team, $score);

	public function updateScore($id, $score, $team);

	public function getTeamSortedMembers($team, $offset, $count);

	public function getTeams();

	public function getUserRank($id, $team);

	public function getScore($id);

	public function getTeamScore($team);

	public function getTeamRank($team);

	public function getTeamMembersCount($team);

	public function getMultipleScore($ids);

}
