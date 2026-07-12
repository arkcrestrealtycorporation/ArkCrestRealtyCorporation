<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Dedicated, Administrator-only controller for the Edit History (Audit Trail) page.
 * Route access is additionally hard-gated by the `admin` middleware (see routes/web.php),
 * and this controller double-checks so it is safe even if ever wired up without it.
 */
class EditHistoryController extends Controller
{
    // Only CUD-type actions belong in the Edit History audit trail
    // (login/logout/approve/reject etc. remain in the general Activity Log panel).
    const CUD_ACTIONS = ['create', 'update', 'delete', 'restore'];

    // Modules whose 'delete' log entries carry enough snapshot data (meta) to be
    // safely recreated by SettingsController::restoreLogRecord(). 'Departmental
    // Expenses' is intentionally excluded here — it already has its own dedicated
    // soft-delete restore flow (Settings > Deleted Records / expenses.restore),
    // so we don't want to offer a second, less reliable "Undo" path for it here.
    const UNDOABLE_MODULES = ['Commission Monitoring', 'Sales & Marketing', 'Human Resource', 'Site Visit Form'];

    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'You do not have permission to view Edit History.');
        }

        $query = ActivityLog::with('user')->whereIn('action', self::CUD_ACTIONS);

        if ($request->filled('module')) {
            $query->where('module', $request->string('module'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        if ($request->filled('search')) {
            $term = $request->string('search');
            $query->where(function ($q) use ($term) {
                $q->where('description', 'like', "%{$term}%")
                  ->orWhere('meta', 'like', "%{$term}%")
                  ->orWhereHas('user', function ($uq) use ($term) {
                      $uq->where('name', 'like', "%{$term}%")
                         ->orWhere('email', 'like', "%{$term}%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        $logs->getCollection()->transform(function ($log) {
            $log->can_undo = $log->action === 'delete'
                && in_array($log->module, self::UNDOABLE_MODULES, true)
                && !empty($log->meta);
            return $log;
        });

        $modules = ActivityLog::whereIn('action', self::CUD_ACTIONS)
            ->whereNotNull('module')
            ->select('module')->distinct()->orderBy('module')->pluck('module');

        $editors = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('settings.edit-history', [
            'logs'    => $logs,
            'modules' => $modules,
            'editors' => $editors,
            'filters' => $request->only(['module', 'user_id', 'date_from', 'date_to', 'search']),
        ]);
    }
}
