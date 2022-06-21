<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        //check multiple roles
        if (strpos($role, '|') !== false) {
            $roles = explode('|', $role);
            foreach ($roles as $role) {
                if ($request->user()->role == $role) {
                    return $next($request);
                }
            }
        } else {
            if ($request->user()->role == $role) {
                return $next($request);
            }
        }
        abort(403, 'Anda tidak memiliki akses halaman tersebut!');
        // return redirect(url('/'));
    }
}
