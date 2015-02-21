<?php namespace Xfactor\Mappers;


use Xfactor\Contracts\Mappers\TeamMapperInterface;

use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Collection as ResponseCollectionInterface;

use Xfactor\Team;

use Xfactor\Contracts\Mappers\WalletMapperInterface;

use Vinelab\Api\MappableTrait;

use Agency\Api\Mappers\ImageMapper;

class TeamMapper implements TeamMapperInterface {

	use MappableTrait;


	public function __construct(ResponseCollectionInterface $collection,
								ImageMapper $image_mapper)
	{
		$this->collection = $collection;
		$this->image_mapper = $image_mapper;
	}

	public function map($team)
	{
		return [
			"id" => $team->getKey(), 
			"title" => $team->title(),
			"slug" => $team->slug(),
			"image" => $this->image_mapper->parseAndFill($team->image),
			"members_count" => $team->total()
		];
	} 

	public function parseAndFill($team)
	{
		$this->team['id'] = $team->getKey();
		$this->team['title'] = $team->title();
		$this->team['slug'] = $team->slug();
		$this->team['image'] = $this->image_mapper->parseAndFill($team->image);
		$this->team['members_count'] = $team->total();
		return $this->team;
	}
}
