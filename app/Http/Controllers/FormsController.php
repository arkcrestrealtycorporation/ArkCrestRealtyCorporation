<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Expense;

class FormsController extends Controller
{
    public function index()
    {
        $departments = Department::with('categories')->get();
        // Get unique requestor names from past commission requests
        $requestorNames = \App\Models\CommissionRequest::whereNotNull('requestor_name')
            ->where('requestor_name', '!=', '')
            ->distinct()
            ->orderBy('requestor_name')
            ->pluck('requestor_name')
            ->toArray();
        return view('forms', compact('departments', 'requestorNames'));
    }

    // Returns next control number: ARCS-MM-NNN-YY
    public function nextControlNumber(Request $request)
    {
        $month = now()->format('m');
        $year  = now()->format('y');
        $key   = 'ctrl_num_' . now()->format('Y_m');

        $count = (int)(\DB::table('app_settings')->where('key', $key)->value('value') ?? 0);
        $seq   = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return response()->json(['control_number' => "ARCS-{$month}-{$seq}-{$year}"]);
    }

    // Increments the counter (called on print/download)
    public function incrementControlNumber(Request $request)
    {
        $key = 'ctrl_num_' . now()->format('Y_m');
        $current = (int)(\DB::table('app_settings')->where('key', $key)->value('value') ?? 0);
        \DB::table('app_settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $current + 1, 'created_at' => now(), 'updated_at' => now()]
        );
        return response()->json(['ok' => true, 'next' => $current + 1]);
    }
}