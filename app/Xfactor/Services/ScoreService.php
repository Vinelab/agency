<?php namespace Xfactor\Services;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Illuminate\Redis\Database as Redis;


use Xfactor\Contracts\Services\ScoreServiceInterface;


class ScoreService implements ScoreServiceInterface {

    
    public function __construct(Redis $redis) 
    {
        $this->redis = $redis;
    }

    public function getHash($id)
    {
        return "users:".$id;
    }


    public function createScore($id, $team)
    {
        
        $this->redis->zadd('users:'.$team, 0, $id.':total');
       
        return $this->redis->hmset($this->getHash($id),[
            'chatting' => 0,
            'sharing' => 0,
            'commenting' => 0,
            'others' => 0
        ]);
    }

    public function createTeamScore($team)
    {
        $this->redis->zadd('team:', 0, $team.':total');
 
        return $this->redis->hmset('teams:'.$team,[
            'chatting' => 0,
            'sharing' => 0,
            'commenting' => 0,
            'others' => 0
        ]);

    }

    public function updateTeamScore($team, $score)
    {
        $old_score = $this->redis->hmget('teams:'.$team, ['chatting', 'sharing', 'commenting', 'others']);
        
        $updated_score = [
            'chatting' => $old_score[0] + $score['chatting'],
            'sharing' => $old_score[1] + $score['sharing'],
            'commenting' => $old_score[2] + $score['commenting'],
            'others' => $old_score[3] + $score['others']
        ];

        $this->redis->zincrby('team:',array_sum($score),$team.':total');

        return $this->redis->hmset('teams:'.$team, $updated_score);

    }

    public function updateScore($id,$score, $team)
    {
        $old_score = $this->redis->hmget($this->getHash($id),['chatting', 'sharing', 'commenting', 'others']);
        
        $updated_score = [
            'chatting' => $old_score[0] + (integer)$score['chatting'],
            'sharing' => $old_score[1] + (integer)$score['sharing'],
            'commenting' => $old_score[2] + (integer)$score['commenting'],
            'others' => $old_score[3] + (integer)$score['others']
        ];


        $this->redis->zincrby('users:'.$team,array_sum($score),$id.':total');
        $this->updateTeamScore($team, $score);

        return $this->redis->hmset($this->getHash($id), $updated_score);
    }

    public function getTeamSortedMembers($team, $offset = 0, $count = 20)
    {
        $result = $this->redis->zrevrangebyscore($this->getHash($team),'+inf','-inf','withscores','limit',$offset, $count);
        return $this->mapMembers($result);
    }

    public function getTeams()
    {
        return $this->redis->zrevrangebyscore('team:','+inf','-inf','withscores');
    }


    public function getUserRank($id, $team)
    {
        return $this->redis->zrevrank($this->getHash($team),$id.':total');
    }

    public function getScore($id)
    {
        $score = $this->redis->hmget($this->getHash($id),['chatting', 'sharing', 'commenting', 'others']);
        return $this->scoreMapper($score);
    }

    public function getTeamScore($team)
    {
        $score =  $this->redis->hmget('teams:'.$team,['chatting', 'sharing', 'commenting', 'others']);
        return $this->scoreMapper($score);
    }

    public function getTeamRank($team)
    {
        return $this->redis->zrevrank('team:',$team.':total');
    }

    public function scoreMapper($score)
    {
        return [
            'chatting' => (integer)$score[0],
            'sharing' => (integer)$score[1],
            'commenting' => (integer)$score[2],
            'others' => (integer)$score[3]
        ];
    }

    public function mapMembers($leaderboard)
    {

        $members = [];

        foreach ($leaderboard as $member) {
            $members[explode(':',$member[0])[0]] = $member[1];
        }

        return $members;

    }

    public function getTeamMembersCount($team)
    {
        return $this->redis->zcard('users:'.$team);
    }

    public function getMultipleScore($ids)
    {

        $scores = [];
        foreach ($ids as $id) {

            $scores[$id] = $this->getScore($id);
        }

        return $scores;
    }


    


}
