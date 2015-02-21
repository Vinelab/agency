<?php namespace Xfactor\Repositories;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use Xfactor\User;
use Agency\Repositories\Repository;
use Xfactor\Contracts\Repositories\UserRepositoryInterface;

 class UserRepository extends Repository implements UserRepositoryInterface {

    protected $per_page = 50;

    /**
     * @var Xfactor\User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->model = $this->user = $user;
    }

    public function createWith( $name,
                                $gigya_id,
                                $avatar,
                                $country,
                                $relation)
    {
        return $this->user->createWith([
                                        'name' => $name,
                                        'gigya_id' => $gigya_id,
                                        'avatar' => $avatar,
                                        'country' => $country],
                                        $relation);
    }

    public function page($input = [])
    {
        $user = $this->user->select(['_id', 'name', 'gigya_id', 'avatar', 'wallet', 'blocked']);

        if (isset($input['sort']) and ! empty($input['sort']))
        {
            $sort  = $input['sort'];
            $order = isset($input['order']) ? $input['order'] : 'desc';

            switch($sort)
            {
                case 'name':
                    $user->orderBy('name', $order);
                break;

                case 'gigya_id':
                    $user->orderBy('gigya_id', $order);
                break;

                case 'wallet-votes':
                    $user->orderBy('wallet.votes', $order);
                break;

            }
        }

        return $user->paginate($this->per_page);
    }

    public function get($ids)
    {
        $user = $this->user->whereIn('gigya_id',$ids);

        return $user->paginate($this->per_page);
    }
 }
