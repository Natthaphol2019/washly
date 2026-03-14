<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDriverRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // อนุญาตเฉพาะคนที่มี role เป็น driver เท่านั้น
        if (Auth::user()?->role !== 'driver') {
            abort(403);
        }

        return $next($request);
    }
}