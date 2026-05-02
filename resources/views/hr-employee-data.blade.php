@extends('layouts.dashboard')
@section('title', 'Employee Data')
@section('content')

<div style="background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);border-radius:20px;padding:36px 40px;margin-bottom:28px;position:relative;overflow:hidden;box-shadow:0 8px 32px rgba(30,69,117,.25);">
    <div style="position:relative;z-index:2;">
        <div style="font-size:12px;font-weight:700;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:8px;">Human Resource</div>
        <h1 style="font-size:28px;font-weight:700;color:white;margin:0 0 8px;">Employee Data</h1>
        <p style="font-size:14px;color:rgba(255,255,255,.75);margin:0;">Employment details for all active users</p>
    </div>
    <div style="position:absolute;top:0;right:0;width:300px;height:100%;pointer-events:none;">
        <div style="position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.05);top:-60px;right:-40px;"></div>
    </div>
</div>

@if(auth()->user()->isAdmin())
<div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;">
    <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;">Add New Employee</div>
    <form method="POST" action="{{ route('settings.employee.add') }}">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:16px;">
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Full Name</label><input type="text" name="name" required class="st-input" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Employee ID</label><input type="text" name="employee_id" class="st-input" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Position</label><input type="text" name="position" class="st-input" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Date Hired</label><input type="date" name="date_hired" class="st-input" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
        </div>
        <button type="submit" style="padding:9px 20px;background:linear-gradient(135deg,#1e4575,#2563eb);color:white;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;">Add Employee</button>
    </form>
</div>
@endif

<div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.08);">
    <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;">All Employees ({{ $activeUsers->count() }})</div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">#</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Name</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Employee ID</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Position</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Date Hired</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Role</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeUsers as $i => $u)
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 14px;color:#94a3b8;">{{ $i + 1 }}</td>
                    <td style="padding:12px 14px;font-weight:600;color:#0f172a;">{{ $u->name }}</td>
                    <td style="padding:12px 14px;color:#64748b;">{{ $u->employee_id ?? '—' }}</td>
                    <td style="padding:12px 14px;color:#64748b;">{{ $u->position ?? '—' }}</td>
                    <td style="padding:12px 14px;color:#64748b;">{{ $u->date_hired ? $u->date_hired->format('M d, Y') : '—' }}</td>
                    <td style="padding:12px 14px;">
                        <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:700;background:{{ $u->role === 'admin' ? '#dbeafe' : '#f1f5f9' }};color:{{ $u->role === 'admin' ? '#1e4575' : '#64748b' }};">{{ ucfirst($u->role ?? 'staff') }}</span>
                    </td>
                    <td style="padding:12px 14px;">
                        <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:700;background:{{ $u->status === 'active' ? '#dcfce7' : '#fef3c7' }};color:{{ $u->status === 'active' ? '#166534' : '#92400e' }};">{{ ucfirst($u->status ?? '—') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
