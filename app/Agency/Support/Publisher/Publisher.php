<?php namespace Agency\Support\Publisher;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */
use View;
class Publisher {
    
    public function display($updating, $edit_post)
    {
    	return View::make('facades.publisher',['updating'=>$updating, 'edit_post'=>$edit_post]);
    }

}
