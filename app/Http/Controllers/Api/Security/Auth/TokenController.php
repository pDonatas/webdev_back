<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Security\Auth;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenController
{
    public function create(Request $request): JsonResponse
    {
        $token = $request->user()->createToken($request->token_name);

        return new JsonResponse(['token' => $token->plainTextToken]);
    }
}
