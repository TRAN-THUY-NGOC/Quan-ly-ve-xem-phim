<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, $expected)
    {
        $u = $request->user();
        if (!$u) abort(403);

        $ok = ($expected === 'Admin' && $u->role_id == 1)
           || ($expected === 'Customer' && $u->role_id == 2);

        abort_if(!$ok, 403);
        return $next($request);
    }
}
