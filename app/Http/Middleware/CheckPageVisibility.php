<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPageVisibility
{
    // Map route names to their setting key — dashboard is never hideable
    const PAGE_MAP = [
        'summary-report'        => 'summary-report',
        'summary-report.yearly' => 'summary-report',
        'departments.admin'     => 'departments',
        'commission-monitoring' => 'commission-monitoring',
        'calendar'              => 'calendar',
        'sales-marketing'       => 'sales-marketing',
        'forms'                 => 'forms',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Admins always have full access
        if (!$user || $user->isAdmin()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        $pageKey   = self::PAGE_MAP[$routeName] ?? null;

        if ($pageKey) {
            $hidden = array_values($user->hidden_pages ?? []);
            $hiddenPages = array_filter($hidden, fn($k) => strpos($k, '.') === false);

            if (in_array($pageKey, $hiddenPages)) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have access to that page.');
            }
        }

        return $next($request);
    }
}
