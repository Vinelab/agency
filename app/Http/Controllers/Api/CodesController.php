<?php namespace Agency\Http\Controllers\Api;


use Agency\Contracts\Repositories\ApplicationRepositoryInterface;
use Agency\Contracts\Repositories\CodeRepositoryInterface;
use Agency\Api\Encryptors\EncryptorInterface;

use Agency\Api\Validators\Contracts\EncryptorValidatorInterface;
use Agency\Api\Validators\CodeValidator;
use Agency\Contracts\Api\CodeManagerInterface;

use Agency\Http\Controllers\Controller;

use Str,Response,Input, Config, Lang;
use Carbon\Carbon;

class CodesController extends Controller {

	public function __construct(CodeRepositoryInterface $code,
								CodeValidator $validator,
								ApplicationRepositoryInterface $application,
								EncryptorInterface $encryptor,
								EncryptorValidatorInterface $encryptorValidator,
								CodeManagerInterface $manager)
	{
		$this->code  = $code;
		$this->validator = $validator;
		$this->application = $application;
		$this->encryptor = $encryptor;
		$this->encryptorValidator = $encryptorValidator;
		$this->manager = $manager;

	}

	public function create()
	{

		$key = Input::get('key');
		try {
			$application = $this->application->findBy("key",$key);
			$secret = $application->secret;
		} catch (Exception $e) {
			return Response::json(["status" => 400, "messages" => Lang::get("messages.invalid_app_credentials")]);
		}


		if($this->encryptorValidator->validate(["key"=>$secret,"data"=>$key.$secret]))
		{
			$hash = $this->encryptor->encrypt($secret,$key.$secret);

			if($hash == Input::get('hash'))
			{

				$code = Str::random($length = 40);

				$input=["app_id"=>$application->id,
						"code"=>$code,
						"valid"=>true
				];

				if($this->validator->validate($input))
				{

					$duration = Carbon::now(config('app.timezone'))->addDays(2)->timestamp;

					$duration_in_seconds = $duration - Carbon::now(config('app.timezone'))->timestamp;

					$this->manager->store($code, $application->id, $duration_in_seconds);
					$code = $this->code->create($application->id,$code,true);
					return Response::json(['status'=>200,'code'=>$code->code, 'duration'=> $duration]);
				}

			}
		} else {
			return Response::json(['status'=>400,'messages'=>Lang::get("messages.invalid_app_credentials")]);
		}

	}

}
