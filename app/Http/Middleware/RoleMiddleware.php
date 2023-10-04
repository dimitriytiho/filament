<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$args): Response
    {
        // Проверяем, есть роль у текущего пользователя ли текущий пользователь
        if ($request->user()?->hasRoles($args)) {
            return $next($request);
        }
        abort(403);
    }
}
