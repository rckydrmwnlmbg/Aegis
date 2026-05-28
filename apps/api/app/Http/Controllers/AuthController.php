<?php

namespace App\Http\Controllers;

use App\Actions\AuthAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    private AuthAction $authAction;

    public function __construct(AuthAction $authAction)
    {
        $this->authAction = $authAction;
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $result = $this->authAction->login(
            $request->input('email'),
            $request->input('password'),
            $request->input('device_name'),
            $request->userAgent(),
            $request->header('X-Correlation-ID')
        );

        return $this->respondSuccess([
            'user' => [
                'id' => $result['user']->id,
                'email' => $result['user']->email,
                'tenant_id' => $result['user']->tenant_id,
            ],
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authAction->logout($request->user(), $request->header('X-Correlation-ID'));

        return $this->respondSuccess();
    }

    public function me(Request $request): JsonResponse
    {
        $data = $this->authAction->me($request->user());

        return $this->respondSuccess($data);
    }
}
