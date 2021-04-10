<?php

namespace Savannabits\JetstreamInertiaGenerator\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JigMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $menuPerms = \Spatie\Permission\Models\Permission::all("name")
                ->filter(function($perm) {
                    return count(explode('.',$perm->name)) === 1;
                })
                ->keyBy('name')
                ->map(fn ($perm) => $request->user()->can($perm->name));
        } else {
            $menuPerms = collect([]);
        }
        $flash = [];
        if (session()->has('success')) $flash["success"] = session('success');
        if (session()->has('error')) $flash["error"] = session('error');
        Inertia::share('flash', $flash);
        Inertia::share('menu_permissions', $menuPerms->toArray());
        Inertia::share('app',collect(config('app'))->only(['name','url'])->toArray());
        return $next($request);
    }
}
