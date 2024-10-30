<?php

namespace App\Http\Controllers;

use App\Models\User as UserModel;
use App\Traits\Helper;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use Helper;
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {

            $data = [];
            $credentials = $request->only('email', 'password');

            //check validation
            $validatedData = Validator::make(
                $request->all(),
                UserModel::rulesLogin()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors()
                    , Response::HTTP_BAD_REQUEST);
            }

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if(is_null($user->email_verified_at)){
                    return $this->responseFormat($data, config('api.account_not_activated')
                        , Response::HTTP_BAD_REQUEST);
                }

                //generate the bearer token
                $bearer_token = $user->id . '-' . time() . '-' . Str::random(60);
                $user->api_token = $bearer_token;

                $user->save();

                return $this->responseFormat($user, config('api.login_success')
                    , Response::HTTP_OK);
            }

            return  $this->responseFormat($data, config('api.login_invalid')
                , Response::HTTP_BAD_REQUEST);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }
}
