<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserCreate;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;

class CreateUserInDatabase implements ShouldQueue
{
    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function handle(UserCreate $event): void
    {
        $request = $event->getRequest();
        $user = $event->getUser();

        $user->update($request);

        $this->userService->updateCustomUserFields($user, $request);
    }
}
