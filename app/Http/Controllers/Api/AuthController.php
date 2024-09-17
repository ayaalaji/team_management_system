<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $credentials = $request->only('email', 'password');

        $result = $this->authService->login($credentials);
        if (!$result) {
            return $this->errorResponse('Unauthorized', 401);
        }

        return $this->successResponse([
            'user' => new AuthResource($result['user']),
            'authorisation' => [
                'token' => $result['token'],
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(RegisterRequest $request){
        $validatedData =$request->validated();

        $result = $this->authService->register($validatedData);

        return $this->successResponse([
            'user' => new AuthResource($result['user']),
            'authorisation' => [
                'token' => $result['token'],
                'type' => 'bearer',
            ]
        ], 'User created successfully');
    }

    public function logout()
    {
         $this->authService->logout();
        return $this->successResponse(null, 'Successfully logged out');
    }

    public function refresh()
    {
         $result = $this->authService->refresh();

        return $this->successResponse([
            'user' => new AuthResource($result['user']),
            'authorisation' => [
                'token' => $result['token'],
                'type' => 'bearer',
            ]
        ]);
    }
}
