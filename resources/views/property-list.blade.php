@extends('layouts.dashboard')
@section('title', 'List of Properties')
@section('content')
<style>
.pl-header{background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);border-radius:16px;padding:28px 32px;margin-bottom:24px;position:relative;overflow:hidden;box-shadow:0 6px 24px rgba(30,69,117,.2)}
.pl-header h1{font-size:22px;font-weight:700;color:white;margin:0 0 4px;position:relative;z-index:2}
.pl-header p{font-size:13px;color:rgba(255,255,255,.7);margin:0;position:relative;z-index:2}
.pl-deco{position:absolute;top:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.05)}
.pl-card{background:white;border-radius:12px;border:1px solid #e8ecf0;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden}
.pl-toolbar{padding:14px 18px;display:flex;align-items:center;gap:10px;border-bottom:1px solid #f1f5f9;flex-wrap:wrap}
.pl-search{position:relative;flex:1;min-width:200px}
.pl-search input{width:100%;padding:8px 12px 8px 34px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#374151;background:#f8fafc}
.pl-search input:focus{outline:none;border-color:#1e4575;background:white}
.pl-search svg{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#94a3b8}
.pl-select{padding:8px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#374151;background:white;cursor:pointer}
.pl-table{width:100%;border-collapse:collapse}
.pl-table thead tr{background:linear-gradient(135deg,#0f2a4a,#1e4575)}
.pl-table thead th{padding:11px 16px;text-align:left;font-size:10px;font-weight:700;color:rgba(255,255,255,.85);text-transform:uppercase;letter-spacing:.7px;white-space:nowrap}
.pl-table tbody tr{border-bottom:1px solid #f1f5f9;transition:background .15s}
.pl-table tbody tr:hover{background:#f8fafc}
.pl-table tbody tr:last-child{border-bottom:none}
.pl-table td{padding:11px 16px;font-size:13px;color:#374151;vertical-align:middle;white-space:nowrap}
.pl-badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700}
.pl-badge-released{background:#dcfce7;color:#166534}
.pl-badge-pending{background:#fef3c7;color:#92400e}
.pl-badge-cancelled{background:#fee2e2;color:#991b1b}
.pl-empty{text-align:center;padding:48px;color:#94a3b8;font-size:13px}
.pl-count{font-size:12px;color:#94a3b8;margin-left:auto}
</style>

<div class="pl-header">
    <div class="pl-deco"></div>
    <h1>List of Properties</h1>
    <p>Reserved properties from the client database</p>
</div>

<div class="pl-card">
    <div class="pl-toolbar">
        <div class="pl-search">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="plSearch" placeholder="Search property, client, developer..." oninput="multiSearch(this.value, 'plTable')">
        </div>
        <select class="pl-select" id="plStatus" style="display:none;"></select>
        <span class="pl-count" id="plCount">{{ $properties->count() }} records</span>
    </div>
    <div style="overflow-x:auto;">
    <table class="pl-table" id="plTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Project Name</th>
                <th>Developer</th>
                <th>Block / Lot</th>
                <th>Lot Area (sqm)</th>
                <th>Client Name</th>
            </tr>
        </thead>
        <tbody id="plBody">
            @forelse($properties as $i => $p)
            <tr>
                <td style="color:#cbd5e1;font-weight:600;text-align:center;">{{ $i + 1 }}</td>
                <td style="font-weight:600;color:#0f172a;">{{ $p->project_name }}</td>
                <td style="color:#64748b;">{{ $p->developer_name ?: '—' }}</td>
                <td>{{ $p->block_lot_number ?: '—' }}</td>
                <td>{{ $p->lot_area ? number_format($p->lot_area, 2) : '—' }}</td>
                <td style="font-weight:600;color:#1e4575;">{{ $p->client_name }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="pl-empty">No properties on record.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<script>
function filterTable() {
    multiSearch(document.getElementById('plSearch').value, 'plTable', 'plCount');
}
</script>
@endsection
