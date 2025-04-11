<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // If no roles are specified, allow access
        if (empty($roles)) {
            return $next($request);
        }
        
        // Check if user has any of the given roles
        foreach ($roles as $role) {
            // Convert string role to ID if needed
            $roleId = is_numeric($role) ? (int)$role : $this->getRoleIdFromName($role);
            
            if ($user->role_id === $roleId) {
                return $next($request);
            }
        }
        
        // User does not have any of the required roles
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
    
    /**
     * Get role ID from role name
     *
     * @param string $roleName
     * @return int
     */
    private function getRoleIdFromName($roleName)
    {
        // Map role names to role IDs
        $roles = [
            'admin' => 1,
            'kasir' => 2,
            'customer' => 3
        ];
        
        return $roles[strtolower($roleName)] ?? 0;
    }
}