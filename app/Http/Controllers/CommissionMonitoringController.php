<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionRequest;

class CommissionMonitoringController extends Controller
{
    public function index()
    {
        $commissionRequests = CommissionRequest::orderBy('date_requested', 'asc')->get();
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $years = CommissionRequest::selectRaw('YEAR(date_requested) as year')
            ->whereNotNull('date_requested')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        return view('commission-monitoring', compact('commissionRequests', 'years', 'isAdmin'));
    }

    public function dashboard()
    {
        return view('commission-dashboard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name'      => 'required|string|max:255',
            'property_details'  => 'nullable|string|max:255',
            'client_name'       => 'required|string|max:255',
            'terms_of_payment'  => 'required|string|max:255',
            'agent_name'        => 'required|string|max:255',
            'number_of_units'   => 'nullable|integer|min:1',
            'net_tcp'           => 'nullable|numeric',
            'commission'        => 'nullable|numeric',
            'mode_of_payment'   => 'nullable|string|max:255',
            'date_requested'    => 'nullable|date',
            'date_released'     => 'nullable|date',
            'status'            => 'nullable|string|max:50',
        ]);
        CommissionRequest::create($validated);
        \App\Models\ActivityLog::log('create', 'Commission Monitoring', "Added commission request for client '{$validated['client_name']}'");
        return redirect()->route('commission-monitoring')->with('success', 'Commission request added.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $record = CommissionRequest::findOrFail($id);
        $clientName = $record->client_name ?? '';
        $projectName = $record->project_name ?? '';
        \App\Models\ActivityLog::log('delete', 'Commission Monitoring', "Deleted commission request ID: {$id} ({$clientName} - {$projectName})", [
            'id'           => $record->id,
            'client_name'  => $record->client_name ?? null,
            'project_name' => $record->project_name ?? null,
            'agent'        => $record->agent ?? null,
            'tcp'          => $record->tcp ?? null,
            'reservation_date' => $record->reservation_date ?? null,
            'status'       => $record->status ?? null,
        ]);
        $record->delete();
        return redirect()->route('commission-monitoring')->with('success', 'Commission request deleted.');
    }
}
