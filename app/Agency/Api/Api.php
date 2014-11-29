<?php namespace Agency\Api;

use Agency\Contracts\Api\ApiInterface;
use Response;

class Api implements ApiInterface {

	public function respond($results,$total,$page = 1)
	{
		return Response::json([
			'status' => 200,
            'data' => $results,
            'total' => intval($total),
            'page' => is_null($page) ? 1 : intval($page)
        ]);
	}
}
