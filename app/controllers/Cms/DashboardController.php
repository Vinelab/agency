<?php namespace Agency\Cms\Controllers;

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
