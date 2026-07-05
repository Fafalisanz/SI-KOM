<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Penggunaan di route: ->middleware('role:1,2')
     * Angka merujuk pada role_id (1 = Admin, 2 = Staff, 3 = Manager)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRole = (string) $request->user()?->role_id;

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
