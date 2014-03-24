<?php namespace Agency\Api;

class Api {

	public static function respond($posts,$total,$page)
	{
		return [
            'results' => $posts,
            'total' => $total,
            'page' => !is_null($page) ? $page : 1
        ];
	}
}