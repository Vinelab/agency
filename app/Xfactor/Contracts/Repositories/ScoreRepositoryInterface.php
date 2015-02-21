<?php namespace Xfactor\Contracts\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */


interface ScoreRepositoryInterface {

    public function create($sharing, $commenting, $chatting, $others);

    public function update($id, $sharing, $commenting, $chatting, $others);

    public function page();

    public function remove($id);

}