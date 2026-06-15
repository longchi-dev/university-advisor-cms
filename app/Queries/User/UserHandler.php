<?php

namespace App\Queries\User;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public function execute(UserQuery $query): LengthAwarePaginator
    {
        $userQuery = User::query()
            ->whereBetween(DB::raw('DATE(created_at)'), [$query->fromDate, $query->toDate])
            ->orderByDesc('created_at');

        if(is_array($query->emails) && count($query->emails) > 0) {
            $userQuery->whereIn('email', $query->emails);
        }

        $paginator = $userQuery->paginate($query->perPage, ['*'], 'page', $query->page);

        $paginator->getCollection()->transform(function (User $user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => null,
                'last_login_ip' => $user->last_login_ip ?? null,
                'last_login_at' => $user->last_login_at?->format('d-m-Y H:i:s') ?? null,
                'created_at' => $user->created_at,
            ];
        });

        return $paginator;
    }
}
