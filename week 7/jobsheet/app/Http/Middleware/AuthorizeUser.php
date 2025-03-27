<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $Role = ' '): Response
    {
        $user = $request->user(); //ambil user yang sedang login
        if ($user-> hasRole($Role)) { //cek role user
            return $next($request); //lanjutkan ke controller
        }
        // Jika tidak memiliki role yang sesuai, tampilkan pesan error
        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
    }
}
