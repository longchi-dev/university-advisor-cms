<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (\Illuminate\Http\Response|RedirectResponse) $next
     * @param string $requiredRole
     * @return RedirectResponse|\Illuminate\Http\Response|mixed|void
     */
    public function handle(Request $request, Closure $next, string $requiredRole)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Map role -> level
        $roleLevels = [
            UserRoleEnum::VIEWER->value  => 1,
            UserRoleEnum::SETTING->value => 2,
            UserRoleEnum::ADMIN->value   => 3,
        ];

        $userRole   = $user->role->value ?? $user->role; // nếu enum hoặc string
        $userLevel  = $roleLevels[$userRole] ?? 0;
        $requiredLevel = $roleLevels[$requiredRole] ?? 99;

        // Admin luôn có quyền
        if ($userRole === UserRoleEnum::ADMIN->value) {
            return $next($request);
        }

        // Quyền ngoại lệ: setting có thể xem llm_logs
        if (
            $userRole === UserRoleEnum::SETTING->value &&
            $request->routeIs('llm_log.index')
        ) {
            return $next($request);
        }

        // Kiểm tra theo level
        if ($userLevel >= $requiredLevel) {
            return $next($request);
        }

        abort(403, 'Permission denied.');
    }
}
