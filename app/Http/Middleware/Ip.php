<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Ip as IpModel;

class Ip
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Кэшируем на 60 секунд, допускаются ip из модели Ip
        $ips = cache()->remember('ips', 60, fn () => IpModel::pluck('ip'));

        // Проверяем ip
        if ($ips->contains($request->ip())) {
            return $next($request);
        }
        abort(403);
    }
}
