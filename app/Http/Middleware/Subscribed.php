<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Response;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        abort_unless($user->hasActiveSubscription(),
            Response::HTTP_UNAUTHORIZED, "You don't have an active subscription");
        return $next($request);
    }
}
