<?php namespace Agency\Http\Controllers\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View, Auth;

class DashboardController extends Controller {

    public function index()
    {
        return View::make('cms.pages.dashboard.index', compact('errors', 'warnings', 'success'));
    }
}
