<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;
use App\Http\Requests\Api\RegisterRequest;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        return $this->response->created();
    }
}