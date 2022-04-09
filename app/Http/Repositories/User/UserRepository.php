<?php

declare(strict_types=1);

namespace App\Http\Repositories\User;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function search(string $query, int $perPage = 10): LengthAwarePaginator
    {
        $query = explode(' ', $query);

        $result =  User::with('userDetails')
            ->where('first_name', 'like', '%' . $query[0] . '%')
            ->orWhere('last_name', 'like', '%' . $query[0] . '%');

        if (count($query) > 1) {
            foreach($query as $value) {
                $result->orWhere('first_name', 'like', '%' . $value . '%')
                    ->orWhere('last_name', 'like', '%' . $value . '%');
            }
        }

        return $result->paginate($perPage);
    }
}
