<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Security\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\LoginRequest;
use App\Http\Requests\Security\RegisterRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['errors' => ['Invalid email or password']], 401);
        }

        return $this->respondWithToken();
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(RegisterRequest $request)
    {
       $user = $this->userService->createUser($request->validated());

        auth()->login($user);

        return $this->respondWithToken();
    }


    private function respondWithToken()
    {
        return new JsonResponse([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }

}
