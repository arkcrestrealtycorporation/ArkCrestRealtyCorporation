<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionRequest;
use App\Models\CommissionRequestSales;
use App\Models\Department;
use App\Models\Expense;
use App\Models\SummaryReport;
use App\Models\TripSchedule;
use App\Models\Note;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        if (strlen($query) < 1) return response()->json([]);

        $user       = auth()->user();
        $hidden     = $user->hidden_pages ?? [];
        $results    = [];

        $canSee = fn(string $page) => !in_array($page, $hidden);

        // ── Finance: Commission Monitoring ──────────────────────────────
        if ($canSee('commission-monitoring')) {
            CommissionRequest::where(function($q) use ($query) {
                $q->where('control_number',   'like', "%$query%")
                  ->orWhere('requestor_name', 'like', "%$query%")
                  ->orWhere('department',     'like', "%$query%")
                  ->orWhere('category',       'like', "%$query%")
                  ->orWhere('status',         'like', "%$query%")
                  ->orWhere('client_name',    'like', "%$query%")
                  ->orWhere('agent_name',     'like', "%$query%")
                  ->orWhere('project_name',   'like', "%$query%");
            })->orderBy('date_requested', 'desc')->limit(20)->get()
            ->each(function($r) use (&$results) {
                $results[] = [
                    'type'         => 'expense',
                    'title'        => ($r->control_number ?? '') . ' — ' . ($r->requestor_name ?? ''),
                    'description'  => ($r->department ?? '') . ' | ' . ($r->category ?? '') . ' | ' . ($r->date_requested ? $r->date_requested->format('M d, Y') : ''),
                    'amount'       => '₱' . number_format($r->requested_amount, 2),
                    'status'       => $r->status,
                    'url'          => '/commission-monitoring',
                    'highlight_id' => 'expense-' . $r->id,
                    'icon'         => 'document',
                ];
            });
        }

        // ── Sales & Marketing: Client Database ──────────────────────────
        if ($canSee('client-database')) {
            CommissionRequestSales::where(function($q) use ($query) {
                $q->where('client_name',   'like', "%$query%")
                  ->orWhere('agent_name',  'like', "%$query%")
                  ->orWhere('project_name','like', "%$query%")
                  ->orWhere('developer_name','like', "%$query%")
                  ->orWhere('block_lot_number','like', "%$query%")
                  ->orWhere('status',      'like', "%$query%");
            })->orderBy('date_requested', 'desc')->limit(20)->get()
            ->each(function($r) use (&$results) {
                $results[] = [
                    'type'        => 'client',
                    'title'       => $r->client_name ?? '',
                    'description' => ($r->project_name ?? '') . ' | ' . ($r->agent_name ?? '') . ' | ' . ($r->date_requested ? $r->date_requested->format('M d, Y') : ''),
                    'amount'      => $r->net_tcp ? '₱' . number_format($r->net_tcp, 2) : null,
                    'status'      => $r->status,
                    'url'         => '/client-database',
                    'icon'        => 'person',
                ];
            });
        }

        // ── Sales & Marketing: Site Visit Database ──────────────────────
        if ($canSee('site-visit-database')) {
            TripSchedule::where(function($q) use ($query) {
                $q->where('client_name',   'like', "%$query%")
                  ->orWhere('agent_name',  'like', "%$query%")
                  ->orWhere('property_name','like', "%$query%")
                  ->orWhere('status',      'like', "%$query%");
            })->orderBy('tripping_date', 'desc')->limit(10)->get()
            ->each(function($r) use (&$results) {
                $results[] = [
                    'type'        => 'trip',
                    'title'       => $r->client_name ?? '',
                    'description' => ($r->property_name ?? '') . ' | ' . ($r->agent_name ?? '') . ' | ' . ($r->tripping_date ? $r->tripping_date->format('M d, Y') : ''),
                    'status'      => $r->status,
                    'url'         => '/site-visit-database',
                    'icon'        => 'location',
                ];
            });
        }

        // ── Finance: Summary Reports ─────────────────────────────────────
        if ($canSee('summary-report')) {
            SummaryReport::where(function($q) use ($query) {
                $q->whereRaw('CAST(year AS CHAR) LIKE ?', ["%$query%"])
                  ->orWhereRaw('CAST(units AS CHAR) LIKE ?', ["%$query%"])
                  ->orWhereRaw('CAST(gross_sales AS CHAR) LIKE ?', ["%$query%"]);
            })->limit(10)->get()
            ->each(function($r) use (&$results) {
                $months = ['','January','February','March','April','May','June','July','August','September','October','November','December'];
                $results[] = [
                    'type'        => 'report',
                    'title'       => 'Summary Report — ' . ($months[$r->month] ?? $r->month) . ' ' . $r->year,
                    'description' => 'Units: ' . $r->units . ' | Gross Sales: ₱' . number_format($r->gross_sales, 2),
                    'url'         => '/summary-report',
                    'icon'        => 'chart',
                ];
            });
        }

        // ── Finance: Departments ─────────────────────────────────────────
        if ($canSee('departments')) {
            Department::where('name', 'like', "%$query%")->limit(5)->get()
            ->each(function($d) use (&$results) {
                $results[] = [
                    'type'        => 'department',
                    'title'       => $d->name . ' Department',
                    'description' => 'View department expenses and budget',
                    'url'         => '/departments',
                    'icon'        => 'building',
                ];
            });
        }

        // ── Page navigation matches ──────────────────────────────────────
        $allPages = [
            'dashboard'              => ['Finance Dashboard',            '/dashboard',            'home',     'dashboard'],
            'departments'            => ['Departmental Expenses',        '/departments',          'building', 'departments'],
            'summary-report'         => ['Summary Report',               '/summary-report',       'chart',    'summary-report'],
            'commission-monitoring'  => ['Commission Monitoring',        '/commission-monitoring','document', 'commission-monitoring'],
            'commission-monitoring.dashboard' => ['Commission Dashboard','/commission-dashboard', 'chart',    'commission-monitoring.dashboard'],
            'calendar'               => ['Calendar',                     '/calendar',             'calendar', 'calendar'],
            'sales-marketing'        => ['Sales & Marketing',            '/sales-marketing',      'chart',    'sales-marketing'],
            'client-database'        => ['Client Database',              '/client-database',      'person',   'client-database'],
            'site-visit-database'    => ['Site Visit Database',          '/site-visit-database',  'location', 'site-visit-database'],
            'sales-calendar'         => ['Sales Calendar',               '/sales-calendar',       'calendar', 'sales-calendar'],
            'forms'                  => ['Forms',                        '/forms',                'document', 'forms'],
            'settings'               => ['Settings',                     '/settings',             'settings', 'settings'],
        ];

        foreach ($allPages as $key => [$title, $url, $icon, $pageKey]) {
            if ($canSee($pageKey) && stripos($title, $query) !== false) {
                $results[] = [
                    'type'        => 'page',
                    'title'       => $title,
                    'description' => 'Go to ' . $title,
                    'url'         => $url,
                    'icon'        => $icon,
                ];
            }
        }

        return response()->json(array_slice($results, 0, 50));
    }
}
