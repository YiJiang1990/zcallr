<?php

namespace App\Http\Controllers\Api;



use Dingo\Api\Auth\Auth;
use JWTFactory;
use JWTAuth;
use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->array(['error' => 'invalid_credentials'])->setStatusCode(401);
            }
        } catch (JWTException $e) {
            return $this->response->array(['error' => 'could_not_create_token'])->setStatusCode(500);
        }

        return $this->respondWithToken($token)->setStatusCode(201);

    }

    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTFactory::getTTL() * 60
        ]);
    }

    public function update()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->response->noContent();
    }
}
