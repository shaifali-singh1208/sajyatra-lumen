<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\IPAccessTraits;
use Symfony\Component\HttpFoundation\Response;

class ValidateIPAccess
{
    use IPAccessTraits;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isValidCallbackIp(self::$web_access)) {
            abort(403, 'Access Denied. Unauthorized Access.');
        }
        return $next($request);
    }
}
