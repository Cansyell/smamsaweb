<?php
// File: app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    $userRole = auth()->user()->role;

    if (!in_array($userRole, $roles)) {
        \Log::warning('Role mismatch', [
            'user_id'    => auth()->id(),
            'user_role'  => $userRole,
            'required'   => $roles,
            'url'        => $request->url(),
        ]);

        return $this->redirectBasedOnRole($userRole);
    }

    return $next($request);
}

    private function redirectBasedOnRole(string $role): Response
    {

        $message = 'Anda tidak memiliki akses ke halaman ini.';

        return match ($role) {
            'admin'     => redirect()->route('dashboard')->with('error', $message),
            'committee' => redirect()->route('committee.dashboard')->with('error', $message),
            'student'   => redirect()->route('student.dashboard')->with('error', $message),
            default     => redirect()->route('login')->with('error', 'Role tidak valid.'),
        };
    }
}