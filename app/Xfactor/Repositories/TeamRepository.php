<?php namespace Xfactor\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Agency\Helper;
use Xfactor\Team;
use Agency\Repositories\Repository;
use Xfactor\Contracts\Repositories\TeamRepositoryInterface;
use Xfactor\User;

 class TeamRepository extends Repository implements TeamRepositoryInterface {

    protected $per_page = 50;

    /**
     * @var Xfactor\Team
     */
    protected $team;

    public function __construct(Team $team)
    {
        $this->model = $this->team = $team;
    }

    public function createWith( $title,
                                $slug,
                                $score,
                                $user_count,
                                $relation)
    {
        return $this->team->createWith([
                                        'title' => $title,
                                        'slug' => $slug,
                                        'score' => $score,
                                        'user_count' => $user_count],
                                        $relation);
    }


    public function update($id, $title, $slug ,$score = null, $user_count = null,$relations)
    {
        $team = $this->find($id);

        $team->fill([
            'title'         => $title,
            'slug'          => $slug,
            'score'          => $score,
            'user_count'      => $user_count
        ]);


        $team->save();

        if (isset($relations[ 'image' ])) {
            $this->updateImage($team, $relations[ 'image' ]);
        }

        
    }

    public function page()
    {
        return $this->team->paginate($this->per_page);
    }

    public function remove($id)
    {
        $team = $this->find($id);
        $team->image()->edge($team->image)->delete();
        return $team->delete();
    }


    public function updateImage($model, $image)
    {
        $relation = $model->image()->save($image);
        $relation->save();
    }

    public function join($id, User $user)
    {
        $team = $this->findByIdOrSlug($id);
      
        return $team->users()->save($user);
    }

    public function members($idOrSlug)
    {
        $team = $this->findByIdOrSlug($idOrSlug);
        return $team->users()->paginate($this->per_page);
    }

    public function score($idOrSlug)
    {
        $team = $this->findByIdOrSlug($idOrSlug);
        $team_score = 0;
        $team->users->each(function($user) use ($team_score)
        {
            $score = $user->score;
            $team_score = $team_score + $score->chatting + $score->sharing + $score->commenting + $score->other;
            
        });
        
        return $team_score;

    }
    
 }
