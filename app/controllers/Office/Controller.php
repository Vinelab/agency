<?php namespace Agency\Cms\Controllers;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Auth;
use Request;
use Carbon\Carbon;
use Controller as BaseController;
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
        if (is_null($state)) return null;
        // accept non-array values allowing passing in just a string.
        $state = (array) $state;

        if (in_array('editing', $state))
        {
            return 'editing';
        }
        elseif (in_array('published', $state))
        {
            return 'published';
        }
        elseif (in_array('scheduled', $state))
        {
            return 'scheduled';
        }
        else
        {
            return '';
        }
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
        // We don't wanna go through anything if the state is null.
        if (is_null($state)) {
            return '';
        }

        $publish_state = $this->pluckState($state);

        // If the admin is trying to post it as a published article but has no publish
        // rights then we'll downgrade it to editing.

        /**
         * @todo Pass the publish state through an enum validation pipeline.
         */

        if (! Auth::hasPermission('publish') && $publish_state == 'published') {
            $publish_state = $this->pluckState('editing');
        }

        return $publish_state;
    }

     /**
     * Format the date using carbon.
     *
     * @param  mixed  $date
     * @param  boolean $instance Set to true to get the Carbon instance.
     * @return string|Carbon\Carbon
     */
    protected function formatDate($date, $instance = false)
    {
        if ( ! $date) return Carbon::now('Asia/Beirut')->toDateTimeString();

        if ( ! $date instanceof Carbon) $date = new Carbon($date);

        return ($instance) ? $date : $date->toDateTimeString();
    }
}


