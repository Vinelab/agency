<?php namespace Xfactor\Mappers;


use Xfactor\Contracts\Mappers\UserMapperInterface;

use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Collection as ResponseCollectionInterface;

use Xfactor\User;

use Xfactor\Contracts\Mappers\WalletMapperInterface;

use Vinelab\Api\MappableTrait;

use Xfactor\Contracts\Mappers\TeamMapperInterface;


class UserMapper implements UserMapperInterface {

	use MappableTrait;


	public function __construct(TeamMapperInterface $team_mapper)
	{
		$this->team_mapper = $team_mapper;
	}

	public function map($user)
	{
		return [
			'id' => $user->id,
			'name' => $user->name,
			'gigya_id' => $user->gigya_id,
			'avatar' => $user->avatar,
			'country' => $user->country,
			'team' => $this->team_mapper->parseAndFill($user->team)
		];

	}

	public function make(Collection $users)
	{
		foreach ($users as $user) {

			$this->collection->push($this->parseAndFill($user));
		}

		return $this->collection;
	}

	public function parseAndFill($user)
	{
		$this->user['id'] = $user->getKey();
		$this->user['name'] = $user->name();
		$this->user['gigya_id'] = $user->gigyaId();
		$this->user['avatar'] = $user->avatar();

		return $this->user;
	}
}
