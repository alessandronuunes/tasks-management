<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTeamMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! config('tasks-management.use_teams')) {
            return $next($request);
        }

        if (! auth()->user()->current_team_id) {
            abort(403, 'No team selected.');
        }

        return $next($request);
    }
}
