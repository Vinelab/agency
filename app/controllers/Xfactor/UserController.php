<?php namespace Xfactor\Cms\Controllers;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use View, Input, Auth;
use Agency\Cms\Controllers\Controller;
use Xfactor\Contracts\Repositories\UserRepositoryInterface;

class UserController extends Controller {

    /**
     * @var Xfactor\Contracts\Repositories\UserRepositoryInterface
     */
    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function index()
    {

        if(Auth::hasPermission('read'))
        {
            $users = $this->users->page(Input::all());
            return View::make('cms.pages.users.list', ['users' => $users]);
        }

        throw new UnauthorizedException; 
    }
}
