<?php namespace Xfactor\Mappers;


use Xfactor\Contracts\Mappers\LeaderboardMapperInterface;
use Xfactor\Contracts\Mappers\UserMapperInterface;
      

use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Collection as ResponseCollectionInterface;

use Xfactor\User;

use Vinelab\Api\MappableTrait;


class LeaderboardMapper implements LeaderboardMapperInterface {

	use MappableTrait;


	public function __construct(ResponseCollectionInterface $collection,
								UserMapperInterface $user_mapper)
	{
		$this->collection = $collection;
		$this->user_mapper = $user_mapper;
	}

	public function map($score)
	{
		return $score;
	}
	
}
