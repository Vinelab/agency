<?php namespace Xfactor\Contracts\Mappers;

interface TeamMapperInterface {

	public function map($teams);

	public function parseAndFill($team);
}
