<?php namespace Agency\Contracts;

interface HelperInterface {

	public function aliasify($string);

	public function cleanHTML($html);

	public function br2nl($html);

	public function div2br($html);

	public function slugify($title, $model = null);

    public function getUniqueId();
    
}
