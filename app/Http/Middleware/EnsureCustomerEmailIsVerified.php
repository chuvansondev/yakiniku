<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_admin && ! $user->hasVerifiedEmail()
            && ! $request->routeIs('verification.*', 'logout', 'locale.update')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('Your email address is not verified.')], 403);
            }

            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
