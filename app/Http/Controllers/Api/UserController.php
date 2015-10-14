<?php namespace Agency\Http\Controllers\Api;

use Api;
use Response;
use Illuminate\Http\Request;
use Agency\Validators\UserValidator;
use Agency\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Agency\Exceptions\InvalidUserAttributesException;
use Agency\Contracts\Repositories\UserRepositoryInterface as Users;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class UserController extends Controller
{
    public function update($user_id, Request $request, UserValidator $validator, Users $users)
    {
        try {
            // make sure it's worth the effort
            $validator->validateUpdate($request->input());
            // find and update the user
            if ($users->updateEmail($user_id, $request->input('email'))) {
                return Response::json(['message' => 'User info updated successfully']);
            }
        } catch (InvalidUserAttributesException $e) {
            return Api::error('Invalid attributes provided for update', 400);
        } catch (ModelNotFoundException $e) {
            return Api::error('The user was not found', 404);
        }
    }
}
