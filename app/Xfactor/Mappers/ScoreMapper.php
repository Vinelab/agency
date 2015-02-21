<?php namespace Xfactor\Mappers;


use Xfactor\Contracts\Mappers\ScoreMapperInterface;

use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Collection as ResponseCollectionInterface;

use Xfactor\Score;

use Vinelab\Api\MappableTrait;

use StdClass;



class ScoreMapper implements ScoreMapperInterface {

	use MappableTrait;


	public function map($score)
	{
		return $score;
	}


	public function parseAndFill($score)
	{
			$this->score['id'] = $score->getKey();
			$this->score['sharing'] = $score->sharing();
			$this->score['commenting'] = $score->commenting();
			$this->score['chatting'] = $score->chatting();
			$this->score['others'] = $score->others();
	}
}
