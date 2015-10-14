<?php namespace Agency\Http\Controllers\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Facades\Helper;
use Auth;
use Request;
use Carbon\Carbon;
use Agency\Http\Controllers\Controller as BaseController;
use Agency\Cms\Exceptions\UnauthorizedException;

class Controller extends BaseController {

    public function __construct()
    {
        if (Auth::check() && ! Request::isOpen() && ! Auth::hasPermission('read')) {
            throw new UnauthorizedException();
        }
    }

    /**
     * return the state.
     *
     * @param  array $state
     * @return String                 editing, published, scheduled.
     */
    public function pluckState($state)
    {
        return Helper::pluckState($state);
    }

     /**
     * Make sure the admin has the right to set the publishing state to what is claimed
     * and no invalid values goes through.
     *
     * @param  string|array $state
     * @return string
     */
    protected function filterPublishState($state)
    {
        return Helper::filterPublishState($state);
    }

    /**
     * Format the date using carbon
     *
     * @param      $date
     * @param bool $instance
     *
     * @return mixed
     */
    protected function formatDate($date, $instance = false)
    {
        return Helper::formatDateUsingCarbon($date, $instance);
    }
}


