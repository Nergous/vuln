<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        foreach ($role as $r) {
            if ($request->user()->type == $r) {
                
                return $next($request);
            }
        }
        return redirect('/')->with('error', 'Недостаточно прав');
    }
}
