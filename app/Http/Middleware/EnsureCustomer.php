<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Admins should use the admin panel, not the customer dashboard
        if ($request->user()->type === UserType::Admin) {
            return redirect('/admin');
        }

        return $next($request);
    }
}
