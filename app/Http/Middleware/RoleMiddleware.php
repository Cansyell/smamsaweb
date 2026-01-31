<?php
// File: app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get user role
        $userRole = auth()->user()->role;

        // Check if user role is in allowed roles
        if (!in_array($userRole, $roles)) {
            // Redirect based on user role
            return $this->redirectBasedOnRole($userRole);
        }

        return $next($request);
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole(string $role): Response
    {
        $message = 'Anda tidak memiliki akses ke halaman ini.';

        switch ($role) {
            case 'admin':
                return redirect()->route('dashboard')->with('error', $message);
            case 'committee':
                return redirect()->route('committee.dashboard')->with('error', $message);
            case 'student':
                return redirect()->route('student.dashboard')->with('error', $message);
            default:
                return redirect()->route('login')->with('error', 'Role tidak valid.');
        }
    }
}