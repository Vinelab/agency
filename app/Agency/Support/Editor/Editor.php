<?php namespace Agency\Support\Editor;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */
use View;
class Editor {
    
    public function display($updating, $edit_post)
    {
    	return View::make('facades.editor',['updating'=>$updating, 'edit_post'=>$edit_post]);
    }

}
