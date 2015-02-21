<?php namespace Xfactor\Contracts\Mappers;

use Xfactor\Score;

interface ScoreMapperInterface {

	public function map($score);

	public function parseAndFill($score);
}
