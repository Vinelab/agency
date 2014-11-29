<?php namespace Agency\Office\Controllers;

use Agency\Contracts\Repositories\ApplicationRepositoryInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Api\Validators\ApplicationValidator;

use Str,Response,Input, Auth;

class ApplicationController extends Controller {

	protected $application;

	public function __construct(ApplicationRepositoryInterface $application,
								ApplicationValidator $validator)
	{

		$this->application = $application;
		$this->validator = $validator;
	}

	public function index()
	{
		return $this->application->all();
	}

	public function update($id)
	{

	}

	public function store()
	{
		if (Auth::hasPermission('create'))
		{
			$key=Str::random($length = 30);
			$secret=Str::random($length = 60);
			$name = Input::get('name');
			$input=[
				"name"=>$name,
				"key"=>$key,
				"secret"=>$secret
			];

			if($this->validator->validate($input))
			{
				$app = $this->application->create($name,$key,$secret);
				// return Response::json(['status'=>200,'key'=>$app->key,'secret'=>$app->secret]);
			}
		}


	}

	public function destroy($id)
	{
		if (Auth::hasPermission('delete'))
        {
            try {
                $this->application->destroy($id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $error = Lang::get('api\applications.not_found');
                return Response::json(['error' => $error], 404);
            }
        }
	}
}
