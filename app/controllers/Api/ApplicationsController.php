<?php namespace Agency\Api\Controllers;

use Agency\Cms\Controllers\Controller;

use Agency\Api\Repositories\Contracts\ApplicationRepositoryInterface;
use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Api\Validators\ApplicationValidator;

use Str,Response,Input;

class ApplicationsController extends Controller {

	protected $application;

	public function __construct(ApplicationRepositoryInterface $application,
								ApplicationValidator $validator,
								SectionRepositoryInterface $sections)
	{
		parent::__construct($sections);

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
		if ($this->admin_permissions->has('create'))
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
		if ($this->admin_permissions->has('delete'))
        {
            try {
                $this->application->destroy($id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $error = Lang::get('api\applications.not_found');
                return Response::json(compact('error'), 404);
            }
        }
	}
}