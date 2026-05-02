@extends('layouts.dashboard')
@section('title', 'ARC Contact List')
@section('content')

<div style="background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);border-radius:20px;padding:36px 40px;margin-bottom:28px;position:relative;overflow:hidden;box-shadow:0 8px 32px rgba(30,69,117,.25);">
    <div style="position:relative;z-index:2;">
        <div style="font-size:12px;font-weight:700;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:8px;">Human Resource</div>
        <h1 style="font-size:28px;font-weight:700;color:white;margin:0 0 8px;">ARC Contact List</h1>
        <p style="font-size:14px;color:rgba(255,255,255,.75);margin:0;">ArkCrest personnel contact directory</p>
    </div>
    <div style="position:absolute;top:0;right:0;width:300px;height:100%;pointer-events:none;">
        <div style="position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.05);top:-60px;right:-40px;"></div>
    </div>
</div>

@if(auth()->user()->isAdmin())
<div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;">
    <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;">Add Contact</div>
    <form method="POST" action="{{ route('settings.personnel-contacts.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px;">
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Name</label><input type="text" name="name" required style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Company</label><input type="text" name="company" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Phone</label><input type="text" name="phone" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Email</label><input type="email" name="email" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
            <div><label style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Facebook</label><input type="text" name="facebook" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;box-sizing:border-box;"></div>
        </div>
        <button type="submit" style="padding:9px 20px;background:linear-gradient(135deg,#1e4575,#2563eb);color:white;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;">Add Contact</button>
    </form>
</div>
@endif

<div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.08);">
    <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;">Contacts ({{ $personnelContacts->count() }})</div>
    @if($personnelContacts->isEmpty())
        <p style="color:#94a3b8;text-align:center;padding:40px 0;">No contacts yet.</p>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Name</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Company</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Phone</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Email</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Facebook</th>
                    @if(auth()->user()->isAdmin())
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($personnelContacts as $c)
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 14px;font-weight:600;color:#0f172a;">{{ $c->name }}</td>
                    <td style="padding:12px 14px;color:#64748b;">{{ $c->company ?? '—' }}</td>
                    <td style="padding:12px 14px;color:#64748b;">{{ $c->phone ?? '—' }}</td>
                    <td style="padding:12px 14px;color:#64748b;">{{ $c->email ?? '—' }}</td>
                    <td style="padding:12px 14px;color:#64748b;">{{ $c->facebook ?? '—' }}</td>
                    @if(auth()->user()->isAdmin())
                    <td style="padding:12px 14px;">
                        <form method="POST" action="{{ route('settings.personnel-contacts.destroy', $c->id) }}" style="display:inline;" onsubmit="return confirm('Remove this contact?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding:4px 10px;background:#fee2e2;color:#991b1b;border:none;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;">Remove</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
