<?php namespace Agency\Http\Controllers\Api;

use Api;
use Input;
use Config;
use Redirect;
use Response;
use Illuminate\Http\Request;
use Agency\Services\ContactService;
use Agency\Http\Controllers\Controller;

/**
 * Class AuthController
 *
 * @category Controller
 * @package  Agency\Http\Controllers\Api
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ContactController extends Controller
{

    /**
     * @param string                              $form the form name/type
     * @param \Agency\Services\ContactService $service
     * @param \Illuminate\Http\Request            $request
     *
     * @return mixed
     */
    public function send($form, ContactService $service, Request $request)
    {
        $status = 'failed';

        $response = $service->contact($form, $request);

        if($response){
            $status = 'success';
        }

        return Response::json(['status' => $status]);
    }

}
