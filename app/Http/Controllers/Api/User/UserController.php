<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Events\UserCreate;
use App\Http\Controllers\Controller;
use App\Http\Repositories\User\UserRepository;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    protected UserService $userService;
    protected UserRepository $userRepository;

    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 10);

        return UserResource::collection(User::with('userDetails')
            ->paginate($perPage));
    }

    public function store(StoreRequest $storeRequest): JsonResponse
    {
        $request = $storeRequest->validated();

        $user = $this->userService->createUser($request);

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateRequest $storeRequest, User $user): JsonResponse
    {
        $request = $storeRequest->validated();

        event(new UserCreate($request, $user));

        return new JsonResponse();
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return new JsonResponse(null, 204);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 10);

        if ($request->get('search') === null) {
            return UserResource::collection(User::with('userDetails')
                ->paginate($perPage));
        }

        return UserResource::collection($this->userRepository->search($request->get('search'), $perPage));
    }
}
