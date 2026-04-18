<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionRequest;
use App\Models\Department;
use App\Models\Expense;
use App\Models\SummaryReport;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }
        
        $results = [];
        
        // Search Commission Requests (All Expenses) - Search EVERYTHING
        $commissionRequests = CommissionRequest::where(function($q) use ($query) {
            $q->where('control_number', 'like', '%' . $query . '%')
              ->orWhere('requestor_name', 'like', '%' . $query . '%')
              ->orWhere('department', 'like', '%' . $query . '%')
              ->orWhere('category', 'like', '%' . $query . '%')
              ->orWhere('status', 'like', '%' . $query . '%')
              ->orWhere('requested_amount', 'like', '%' . $query . '%')
              ->orWhere('total_expenses', 'like', '%' . $query . '%')
              ->orWhere('amount_returned', 'like', '%' . $query . '%')
              ->orWhereRaw('CAST(requested_amount AS CHAR) LIKE ?', ['%' . $query . '%'])
              ->orWhereRaw('CAST(total_expenses AS CHAR) LIKE ?', ['%' . $query . '%'])
              ->orWhereRaw('CAST(amount_returned AS CHAR) LIKE ?', ['%' . $query . '%']);
        })
        ->orderBy('date_requested', 'desc')
        ->limit(50)
        ->get();
        
        foreach ($commissionRequests as $request) {
            $dateRequested = $request->date_requested ? $request->date_requested->format('M d, Y') : 'No date';
            
            $results[] = [
                'type' => 'expense',
                'title' => $request->control_number . ' - ' . $request->requestor_name,
                'description' => $request->department . ' | ' . $request->category . ' | ' . $dateRequested,
                'amount' => '₱' . number_format($request->requested_amount, 2),
                'status' => $request->status,
                'url' => '/departments',
                'highlight_id' => 'expense-' . $request->id,
                'icon' => 'document'
            ];
        }
        
        // Search Departments - Increased limit
        $departments = Department::where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();
        
        foreach ($departments as $dept) {
            $results[] = [
                'type' => 'department',
                'title' => $dept->name . ' Department',
                'description' => 'View department expenses and details',
                'url' => '/departments',
                'icon' => 'building'
            ];
        }
        
        // Search Summary Reports - Search ALL fields including numbers
        $summaryReports = SummaryReport::where(function($q) use ($query) {
            $q->where('month', 'like', '%' . $query . '%')
              ->orWhere('year', 'like', '%' . $query . '%')
              ->orWhere('units', 'like', '%' . $query . '%')
              ->orWhere('gross_sales', 'like', '%' . $query . '%')
              ->orWhere('coh', 'like', '%' . $query . '%')
              ->orWhereRaw('CAST(units AS CHAR) LIKE ?', ['%' . $query . '%'])
              ->orWhereRaw('CAST(year AS CHAR) LIKE ?', ['%' . $query . '%'])
              ->orWhereRaw('CAST(gross_sales AS CHAR) LIKE ?', ['%' . $query . '%'])
              ->orWhereRaw('CAST(coh AS CHAR) LIKE ?', ['%' . $query . '%']);
        })
        ->limit(20)
        ->get();
        
        foreach ($summaryReports as $report) {
            $results[] = [
                'type' => 'report',
                'title' => 'Summary Report - ' . $report->month . ' ' . $report->year,
                'description' => 'Units: ' . $report->units . ' | Gross Sales: ₱' . number_format($report->gross_sales, 2) . ' | COH: ₱' . number_format($report->coh, 2),
                'url' => '/summary-report',
                'icon' => 'chart'
            ];
        }
        
        // Add navigation pages if they match
        $pages = [
            ['title' => 'Dashboard', 'description' => 'View overview and statistics', 'url' => '/dashboard', 'icon' => 'home'],
            ['title' => 'Departments', 'description' => 'View all departments', 'url' => '/departments', 'icon' => 'building'],
            ['title' => 'Summary Report', 'description' => 'Monthly and yearly reports', 'url' => '/summary-report', 'icon' => 'chart'],
            ['title' => 'Settings', 'description' => 'System configuration', 'url' => '/settings', 'icon' => 'settings'],
        ];
        
        foreach ($pages as $page) {
            if (stripos($page['title'], $query) !== false || stripos($page['description'], $query) !== false) {
                $results[] = array_merge($page, ['type' => 'page']);
            }
        }
        
        // Add common search terms that map to pages
        $searchTerms = [
            'units' => ['title' => 'Summary Report - Units', 'description' => 'View units sold per month', 'url' => '/summary-report', 'icon' => 'chart', 'type' => 'page'],
            'gross sales' => ['title' => 'Summary Report - Gross Sales', 'description' => 'View gross sales per month', 'url' => '/summary-report', 'icon' => 'chart', 'type' => 'page'],
            'coh' => ['title' => 'Summary Report - COH', 'description' => 'View COH (Cost of House) per month', 'url' => '/summary-report', 'icon' => 'chart', 'type' => 'page'],
            'net sales' => ['title' => 'Summary Report - Net Sales', 'description' => 'View net sales per month', 'url' => '/summary-report', 'icon' => 'chart', 'type' => 'page'],
            'total expenses' => ['title' => 'Dashboard - Total Expenses', 'description' => 'View total expenses', 'url' => '/dashboard', 'icon' => 'home', 'type' => 'page'],
            'expenses' => ['title' => 'Departments - All Expenses', 'description' => 'View all departmental expenses', 'url' => '/departments', 'icon' => 'building', 'type' => 'page'],
        ];
        
        foreach ($searchTerms as $term => $pageInfo) {
            if (stripos($term, $query) !== false || stripos($query, $term) !== false) {
                $results[] = $pageInfo;
            }
        }
        
        return response()->json($results);
    }
}
