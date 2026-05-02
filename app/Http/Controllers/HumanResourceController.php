<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SalesAgent;

class HumanResourceController extends Controller
{
    public function index()
    {
        $totalEmployees = User::whereNotIn('status', ['pre_registered', 'deleted'])
            ->whereNotNull('employee_id')
            ->count();

        $totalAgents = SalesAgent::count();

        $totalAdmins = User::where('role', 'admin')
            ->whereNotIn('status', ['pre_registered', 'deleted'])
            ->count();

        return view('human-resource', compact('totalEmployees', 'totalAgents', 'totalAdmins'));
    }
}
