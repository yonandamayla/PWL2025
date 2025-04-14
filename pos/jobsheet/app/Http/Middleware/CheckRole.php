<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect('/login');
        }
        
        // Check if user has one of the required roles
        $userRoleId = $request->user()->role_id;
        
        // Convert passed role strings to role IDs
        $allowedRoleIds = [];
        foreach ($roles as $role) {
            $allowedRoleIds[] = (int)$role;
        }
        
        if (in_array($userRoleId, $allowedRoleIds)) {
            return $next($request);
        }
        
        // If role doesn't match, redirect with error
        return redirect('/')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
    }
}