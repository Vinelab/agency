<?php namespace Agency\Support\Which;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Auth;

class Artists {

   public function current()
   {
        return Auth::getArtist();
   }
}
