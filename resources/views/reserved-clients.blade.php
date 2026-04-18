@extends('layouts.dashboard')
@section('title', 'List of Clients')
@section('content')
<style>
.lc-header{background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);border-radius:20px;padding:36px 40px;margin-bottom:28px;position:relative;overflow:hidden;box-shadow:0 8px 32px rgba(30,69,117,.25)}
.lc-eyebrow{font-size:12px;font-weight:700;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:8px}
.lc-title{font-size:28px;font-weight:700;color:white;margin:0 0 8px}
.lc-sub{font-size:14px;color:rgba(255,255,255,.75);margin:0;display:flex;align-items:center;gap:5px}
.lc-deco{position:absolute;top:0;right:0;width:300px;height:100%;pointer-events:none}
.lc-circle{position:absolute;border-radius:50%;background:rgba(255,255,255,.06)}
.lc-c1{width:220px;height:220px;top:-60px;right:-40px}
.lc-c2{width:140px;height:140px;top:40px;right:120px}
.lc-c3{width:90px;height:90px;bottom:-20px;right:60px}
.lc-card{background:white;border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,.06),0 4px 16px rgba(0,0,0,.04);overflow:hidden;border:1px solid #f1f5f9}
.lc-head{padding:16px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;background:#f8fafc}
.lc-badge{display:inline-block;padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;background:#dbeafe;color:#1e40af}
.lc-search{position:relative}
.lc-search svg{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#94a3b8}
.lc-search input{padding:8px 12px 8px 34px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:12px;color:#111827;background:white;width:320px;transition:all .2s}
.lc-search input:focus{outline:none;border-color:#1e4575;box-shadow:0 0 0 3px rgba(30,69,117,.08)}
.lc-table{width:100%;border-collapse:collapse;min-width:700px}
.lc-table thead tr{background:linear-gradient(135deg,#0f2a4a,#1e4575)}
.lc-table thead th{padding:13px 18px;text-align:left;font-size:10px;font-weight:700;color:rgba(255,255,255,.85);text-transform:uppercase;letter-spacing:.8px;white-space:nowrap;border-right:1px solid rgba(255,255,255,.08)}
.lc-table thead th:last-child{border-right:none}
.lc-table tbody tr{border-bottom:1px solid #f1f5f9;transition:background .15s}
.lc-table tbody tr:nth-child(even){background:#fafbfc}
.lc-table tbody tr:hover{background:#eff6ff}
.lc-table tbody tr:last-child{border-bottom:none}
.lc-table td{padding:12px 18px;font-size:13px;color:#374151;vertical-align:middle;white-space:nowrap}
.lc-name{font-weight:700;color:#0f172a}
.lc-muted{color:#64748b;font-size:12px}
.lc-tag{display:inline-block;background:#f1f5f9;color:#374151;border-radius:20px;padding:2px 10px;font-size:11px;font-weight:600;margin:1px 2px}
.lc-empty{text-align:center;padding:56px 20px;color:#94a3b8}
.lc-btn{padding:5px 14px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;border:none;transition:all .2s;white-space:nowrap;text-decoration:none;display:inline-block}
.lc-btn-view{background:linear-gradient(135deg,#1e4575,#2563eb);color:white}
.lc-btn-edit{background:linear-gradient(135deg,#A37929,#d4a03a);color:white}
.lc-btn-del{background:linear-gradient(135deg,#7f1d1d,#b91c1c);color:white}
.lc-btn:hover{transform:translateY(-1px);opacity:.9}
/* Edit modal */
.lc-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center}
.lc-modal.open{display:flex}
.lc-modal-box{background:white;border-radius:16px;width:500px;max-width:95vw;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.lc-modal-hdr{background:linear-gradient(135deg,#1e4575,#2563eb);padding:16px 22px;display:flex;align-items:center;justify-content:space-between}
.lc-modal-body{padding:22px}
.lc-label{font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:5px}
.lc-input{padding:8px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#374151;font-family:inherit;width:100%;box-sizing:border-box}
.lc-input:focus{outline:none;border-color:#1e4575;box-shadow:0 0 0 3px rgba(30,69,117,.08)}
</style>

<div class="lc-header">
    <div style="position:relative;z-index:2;">
        <div class="lc-eyebrow">Client Database</div>
        <h1 class="lc-title">List of Clients</h1>
        <p class="lc-sub">
            <svg style="width:15px;height:15px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            All clients from the client database
        </p>
    </div>
    <div class="lc-deco">
        <div class="lc-circle lc-c1"></div>
        <div class="lc-circle lc-c2"></div>
        <div class="lc-circle lc-c3"></div>
    </div>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border-left:3px solid #22c55e;color:#16a34a;padding:10px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;font-weight:500">&#10003; {{ session('success') }}</div>
@endif

<div class="lc-card">
    <div class="lc-head">
        <div style="display:flex;align-items:center;gap:10px;">
            <h2 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">List of Clients</h2>
            <span class="lc-badge">{{ $clients->count() }} record{{ $clients->count() != 1 ? 's' : '' }}</span>
        </div>
        <div class="lc-search">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="lcSearch" placeholder="Search by name, email, phone..." oninput="multiSearch(this.value, 'rcTable')">
        </div>
    </div>

    @if($clients->isEmpty())
    <div class="lc-empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:44px;height:44px;margin:0 auto 12px;display:block;opacity:.3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p style="font-size:13px;font-weight:500;">No clients in the database yet.</p>
    </div>
    @else
    <div style="overflow-x:auto">
        <table class="lc-table" id="rcTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($clients as $i => $c)
            @php
                $trips = $tripData->get($c->client_name, collect());
                $emails = $trips->pluck('client_email')->filter()->unique()->values();
                $phones = $trips->map(fn($t) => trim(($t->client_phone_code ?? '+63').' '.ltrim($t->client_phone ?? '','0')))->filter()->unique()->values();
                $contact = $contactMap->get($c->client_name);
                $address = $contact?->address ?? '';
            @endphp
            <tr>
                <td style="color:#cbd5e1;font-size:11px;font-weight:600;">{{ $i + 1 }}</td>
                <td><div class="lc-name">{{ $c->client_name }}</div></td>
                <td>
                    @forelse($emails as $email)
                        <span class="lc-tag">{{ $email }}</span>
                    @empty <span class="lc-muted">—</span>
                    @endforelse
                </td>
                <td>
                    @forelse($phones as $phone)
                        <span class="lc-tag">{{ $phone }}</span>
                    @empty <span class="lc-muted">—</span>
                    @endforelse
                </td>
                <td><div class="lc-muted">{{ $address ?: '—' }}</div></td>
                <td>
                    <div style="display:flex;gap:6px;align-items:center;">
                        <a href="{{ route('client-database') }}?highlight={{ $c->id }}" class="lc-btn lc-btn-view">View</a>
                        <button class="lc-btn lc-btn-edit" onclick="openEdit('{{ addslashes($c->client_name) }}', '{{ addslashes($address) }}')">Edit</button>
                        <form method="POST" action="{{ route('client-database.destroy', $c->id) }}" onsubmit="return confirm('Delete {{ addslashes($c->client_name) }}?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="lc-btn lc-btn-del">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Edit Address Modal --}}
<div class="lc-modal" id="lcEditModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="lc-modal-box">
        <div class="lc-modal-hdr">
            <div style="color:white;font-size:15px;font-weight:700;">Edit Client Info</div>
            <button onclick="document.getElementById('lcEditModal').classList.remove('open')" style="background:rgba(255,255,255,.15);border:none;color:white;width:28px;height:28px;border-radius:7px;cursor:pointer;font-size:16px;">&times;</button>
        </div>
        <div class="lc-modal-body">
            <form id="lcEditForm" method="POST" action="{{ route('clients.store') }}">
                @csrf
                <input type="hidden" name="_method" id="lcMethod" value="POST">
                <input type="hidden" name="name" id="lc_name">
                <div style="margin-bottom:14px;">
                    <label class="lc-label">Client Name</label>
                    <input class="lc-input" type="text" id="lc_name_display" readonly style="background:#f8fafc;color:#64748b;">
                </div>
                <div style="margin-bottom:18px;">
                    <label class="lc-label">Address</label>
                    <input class="lc-input" type="text" name="address" id="lc_address" placeholder="Enter address...">
                </div>
                <div style="display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button" onclick="document.getElementById('lcEditModal').classList.remove('open')" style="padding:9px 18px;background:#f1f5f9;color:#374151;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Cancel</button>
                    <button type="submit" style="padding:9px 22px;background:linear-gradient(135deg,#1e4575,#2563eb);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterLc(q) {
    const keywords = q.toLowerCase().trim().split(/\s+/).filter(Boolean);
    document.querySelectorAll('#lcTable tbody tr').forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = keywords.length === 0 || keywords.every(k => text.includes(k)) ? '' : 'none';
    });
}

function openEdit(name, address) {
    document.getElementById('lc_name').value = name;
    document.getElementById('lc_name_display').value = name;
    document.getElementById('lc_address').value = address;
    // Check if client record exists
    document.getElementById('lcEditModal').classList.add('open');
}
</script>
@endsection
