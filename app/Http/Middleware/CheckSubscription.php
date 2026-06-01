<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->isActive()) {
            // AJAX request হলে JSON response
            if ($request->expectsJson()) {
                return response()->json(['message' => 'সাবস্ক্রিপশন মেয়াদ শেষ।'], 403);
            }

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'আপনার সাবস্ক্রিপশন মেয়াদ শেষ হয়েছে। অ্যাডমিনের সাথে যোগাযোগ করুন।'
            ]);
        }

        return $next($request);
    }
}
