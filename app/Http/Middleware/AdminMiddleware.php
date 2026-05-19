<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    private string $superAdminEmail = 'raymondmunene5@gmail.com';

    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super admin email is always treated as admin regardless of DB role
        if ($user->email === $this->superAdminEmail || $user->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('voter.index');
    }
}