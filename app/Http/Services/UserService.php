<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $request): User
    {
        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => 'secret',
            'country' => $request['country'] ?? 'LTU',
        ]);

        $this->updateCustomUserFields($user, $request);

        $user->save();

        return $user;
    }

    public function updateCustomUserFields(User $user, array $request): void
    {
        $user->password = Hash::make($request['password']);

        if (!isset($request['address'])) {
            $user->userDetails?->delete();
        }

        if (isset($request['address'])) {
            if ($user->userDetails) {
                $user->userDetails()->update([
                    'address' => $request['address']
                ]);
            } else {
                $user->userDetails()->create([
                    'address' => $request['address']
                ]);
            }
        }

        $user->save();
    }
}
