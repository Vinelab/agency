<?php namespace Xfactor\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Xfactor\Score;
use Agency\Repositories\Repository;
use Xfactor\Contracts\Repositories\ScoreRepositoryInterface;

 class ScoreRepository extends Repository implements ScoreRepositoryInterface {

    protected $per_page = 50;

    /**
     * @var Xfactor\Score
     */
    protected $score;

    public function __construct(Score $score)
    {
        $this->model = $this->score = $score;
    }

    public function create($sharing, $commenting, $chatting, $others)
    {
        return $this->score->create([
            'sharing' => $sharing,
            'commenting' => $commenting,
            'chatting' => $chatting,
            'others' => $others
        ]);
    }

    public function update($id, $sharing, $commenting, $chatting, $others)
    {
        $score = $this->find($id);

        $score->fill([
            'sharing'         => $score->sharing+$sharing,
            'commenting'          => $score->commenting+$commenting,
            'chatting'          => $score->chatting+$chatting,
            'others'      => $score->others+$others
        ]);

        if ($score->save())
        {
            return $score;
        }
    }

    public function page()
    {
        return $this->score->paginate($this->per_page);
    }

    public function remove($id)
    {
        $score = $this->find($id);
        return $score->delete();
    }

    
 }
