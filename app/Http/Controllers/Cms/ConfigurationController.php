<?php namespace Agency\Http\Controllers\Cms;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use View;

class ConfigurationController extends Controller {

    public function index()
    {
        return View::make('cms.pages.configuration.index', compact('sections'));
    }
}
