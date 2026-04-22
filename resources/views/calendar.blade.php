@extends('layouts.dashboard')

@section('content')
@php
    $monthNames  = ['','January','February','March','April','May','June','July','August','September','October','November','December'];
    $dayNames    = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    $firstDay    = (int) date('w', mktime(0,0,0,$month,1,$year));
    $daysInMonth = (int) date('t', mktime(0,0,0,$month,1,$year));
    $today       = date('Y-m-d');
    $prevMonth   = $month == 1 ? 12 : $month - 1;
    $prevYear    = $month == 1 ? $year - 1 : $year;
    $nextMonth   = $month == 12 ? 1 : $month + 1;
    $nextYear    = $month == 12 ? $year + 1 : $year;
    $totalEvents = collect($releasesByDay ?? [])->sum(fn($e) => count($e));
@endphp

<style>
.cal-page { display:flex;flex-direction:column;height:calc(100vh - 62px - 60px);gap:0; }

/* Top bar */
.cal-topbar {
    display:flex;align-items:center;justify-content:space-between;
    padding:0 0 16px;flex-shrink:0;
}
.cal-page-title { font-size:28px;font-weight:700;color:#1e4575;letter-spacing:-.3px; }
.cal-page-sub { font-size:12px;color:#94a3b8;margin-top:2px; }
.cal-controls { display:flex;align-items:center;gap:10px; }
.cal-nav-btn {
    display:inline-flex;align-items:center;justify-content:center;
    width:32px;height:32px;border-radius:8px;
    background:white;border:1.5px solid #e2e8f0;
    color:#1e4575;text-decoration:none;font-size:16px;font-weight:700;
    transition:all .2s;
}
.cal-nav-btn:hover { background:#1e4575;color:white;border-color:#1e4575; }
.cal-month-pill {
    background:linear-gradient(135deg,#1e4575,#2563eb);
    color:white;padding:6px 20px;border-radius:20px;
    font-size:14px;font-weight:700;letter-spacing:.3px;
    min-width:160px;text-align:center;
}
.cal-today-btn {
    padding:6px 14px;background:white;color:#1e4575;
    border:1.5px solid #1e4575;border-radius:8px;
    text-decoration:none;font-size:12px;font-weight:600;
    transition:all .2s;
}
.cal-today-btn:hover { background:#1e4575;color:white; }
.cal-year-sel {
    padding:6px 10px;border:1.5px solid #e2e8f0;border-radius:8px;
    font-size:13px;font-weight:500;color:#374151;background:white;
    cursor:pointer;outline:none;
}

/* Stats bar */
.cal-stats {
    display:flex;gap:12px;margin-bottom:14px;flex-shrink:0;
}
.cal-stat-card {
    background:white;border:1px solid #e8ecf0;border-radius:10px;
    padding:10px 16px;display:flex;align-items:center;gap:10px;
    box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.cal-stat-icon {
    width:32px;height:32px;border-radius:8px;
    background:linear-gradient(135deg,#e8edf5,#dce6f5);
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.cal-stat-val { font-size:16px;font-weight:700;color:#1e4575; }
.cal-stat-lbl { font-size:10px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px; }

/* Calendar grid */
.cal-grid-wrap {
    flex:1;background:white;border-radius:12px;
    box-shadow:0 2px 12px rgba(0,0,0,.06);
    overflow:hidden;border:1px solid #e8ecf0;
    display:flex;flex-direction:column;min-height:0;
}
.cal-day-headers {
    display:grid;grid-template-columns:repeat(7,1fr);
    background:#f8fafc;border-bottom:2px solid #e8ecf0;
    flex-shrink:0;
}
.cal-day-hdr {
    padding:10px 0;text-align:center;
    font-size:11px;font-weight:700;color:#64748b;
    letter-spacing:.6px;text-transform:uppercase;
}
.cal-day-hdr.weekend { color:#94a3b8; }
.cal-days {
    display:grid;grid-template-columns:repeat(7,1fr);
    flex:1;min-height:0;
}
.cal-cell {
    border-right:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9;
    padding:6px 7px;display:flex;flex-direction:column;
    overflow:hidden;transition:background .15s;
}
.cal-cell:nth-child(7n) { border-right:none; }
.cal-cell.empty { background:#fafbfc; }
.cal-cell.weekend { background:#fafbfc; }
.cal-cell.today { background:linear-gradient(135deg,#eff6ff,#e8f0fe); }
.cal-cell:not(.empty):not(.today):hover { background:#f8faff; }
.cal-day-num {
    display:inline-flex;align-items:center;justify-content:center;
    width:22px;height:22px;border-radius:50%;
    font-size:12px;font-weight:500;color:#374151;
    align-self:flex-end;flex-shrink:0;margin-bottom:3px;
}
.cal-cell.today .cal-day-num {
    background:linear-gradient(135deg,#1e4575,#2563eb);
    color:white;font-weight:700;
    box-shadow:0 2px 6px rgba(30,69,117,.3);
}
.cal-cell.weekend .cal-day-num { color:#c4c9d4; }
.cal-event {
    background:linear-gradient(135deg,#1e4575,#2563eb);
    color:white;border-radius:4px;padding:2px 6px;
    font-size:10px;margin-bottom:2px;cursor:pointer;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    flex-shrink:0;transition:opacity .15s;
    box-shadow:0 1px 3px rgba(30,69,117,.2);
}
.cal-event:hover { opacity:.85; }
.cal-more {
    font-size:9px;color:#94a3b8;text-align:right;
    margin-top:1px;flex-shrink:0;font-weight:600;
}

/* Legend */
.cal-legend {
    display:flex;align-items:center;gap:16px;
    padding:10px 0 0;font-size:11px;color:#64748b;flex-shrink:0;
}
</style>

<div class="cal-page">
    {{-- Top Bar --}}
    <div style="background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);border-radius:20px;padding:36px 40px;margin-bottom:28px;position:relative;overflow:hidden;box-shadow:0 8px 32px rgba(30,69,117,.25);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
        <div style="position:relative;z-index:2;">
            <div style="font-size:12px;font-weight:700;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:8px;">Finance</div>
            <h1 style="font-size:28px;font-weight:700;color:white;margin:0 0 8px;">Calendar</h1>
            <p style="font-size:14px;color:rgba(255,255,255,.75);margin:0;">Commission release schedule &bull; {{ $monthNames[$month] }} {{ $year }}</p>
        </div>
        <div class="cal-controls" style="position:relative;z-index:2;">
            <form method="GET" action="{{ route('calendar') }}" style="display:flex;align-items:center;gap:6px;">
                <input type="hidden" name="month" value="{{ $month }}">
                <select name="year" class="cal-year-sel" onchange="this.form.submit()" style="background:rgba(255,255,255,.15);color:white;border:1.5px solid rgba(255,255,255,.3);border-radius:8px;padding:6px 10px;font-size:13px;font-weight:600;">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }} style="color:#1e4575;background:white;">{{ $y }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('calendar', ['month'=>$prevMonth,'year'=>$prevYear]) }}" class="cal-nav-btn" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3);color:white;">&#8249;</a>
            <span class="cal-month-pill" style="background:rgba(255,255,255,.15);color:white;border:1.5px solid rgba(255,255,255,.3);">{{ $monthNames[$month] }} {{ $year }}</span>
            <a href="{{ route('calendar', ['month'=>$nextMonth,'year'=>$nextYear]) }}" class="cal-nav-btn" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3);color:white;">&#8250;</a>
            <a href="{{ route('calendar', ['month'=>date('n'),'year'=>date('Y')]) }}" class="cal-today-btn" style="background:rgba(255,255,255,.2);color:white;border:1.5px solid rgba(255,255,255,.3);">Today</a>
        </div>
        <div style="position:absolute;top:0;right:0;width:300px;height:100%;pointer-events:none;">
            <div style="position:absolute;width:220px;height:220px;top:-60px;right:-40px;border-radius:50%;background:rgba(255,255,255,.06);"></div>
            <div style="position:absolute;width:140px;height:140px;top:20px;right:120px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="cal-stats">
        <div class="cal-stat-card">
            <div class="cal-stat-icon">
                <svg fill="none" stroke="#1e4575" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="cal-stat-val">{{ $daysInMonth }}</div>
                <div class="cal-stat-lbl">Days this month</div>
            </div>
        </div>
        <div class="cal-stat-card">
            <div class="cal-stat-icon" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">
                <svg fill="none" stroke="#059669" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="cal-stat-val" style="color:#059669;">{{ $totalEvents }}</div>
                <div class="cal-stat-lbl">Releases this month</div>
            </div>
        </div>
        <div class="cal-stat-card">
            <div class="cal-stat-icon" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                <svg fill="none" stroke="#d97706" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="cal-stat-val" style="color:#d97706;">{{ date('d') }}</div>
                <div class="cal-stat-lbl">Today — {{ date('F d, Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="cal-grid-wrap">
        <div class="cal-day-headers">
            @foreach($dayNames as $i => $d)
            <div class="cal-day-hdr {{ in_array($i,[0,6]) ? 'weekend' : '' }}">{{ $d }}</div>
            @endforeach
        </div>
        <div class="cal-days">
            @for($i = 0; $i < $firstDay; $i++)
            <div class="cal-cell empty"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateStr   = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $isToday   = $dateStr === $today;
                $events    = $releasesByDay->get($day, collect());
                $col       = ($firstDay + $day - 1) % 7;
                $isWeekend = $col === 0 || $col === 6;
                $cls       = $isToday ? 'today' : ($isWeekend ? 'weekend' : '');
            @endphp
            <div class="cal-cell {{ $cls }}">
                <span class="cal-day-num">{{ $day }}</span>
                @foreach($events->take(2) as $event)
                <div class="cal-event" onclick="showEventDetail({{ $event->id }})" title="{{ $event->agent_name }}">
                    {{ $event->agent_name }}
                </div>
                @endforeach
                @if($events->count() > 2)
                <div class="cal-more">+{{ $events->count()-2 }} more</div>
                @endif
            </div>
            @endfor

            @php $rem = ($firstDay + $daysInMonth) % 7; @endphp
            @if($rem > 0)
                @for($i = 0; $i < (7 - $rem); $i++)
                <div class="cal-cell empty"></div>
                @endfor
            @endif
        </div>
    </div>

    {{-- Legend --}}
    <div class="cal-legend">
        <div style="display:flex;align-items:center;gap:6px;">
            <div style="width:10px;height:10px;border-radius:3px;background:linear-gradient(135deg,#1e4575,#2563eb);"></div>
            Commission Released
        </div>
    </div>
</div>

{{-- Event Detail Modal --}}
<div id="calEventModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:white;border-radius:14px;width:440px;max-width:95vw;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="background:linear-gradient(135deg,#1e4575,#2563eb);padding:18px 22px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="color:rgba(255,255,255,.65);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px;">Release Details</div>
                <div style="color:white;font-size:16px;font-weight:700;" id="calModalTitle">—</div>
            </div>
            <button onclick="document.getElementById('calEventModal').style.display='none'" style="background:rgba(255,255,255,.15);border:none;color:white;width:28px;height:28px;border-radius:7px;cursor:pointer;font-size:16px;line-height:1;">&times;</button>
        </div>
        <div style="padding:20px 22px;" id="calEventBody"></div>
    </div>
</div>

<script>
const calEvents = @json($releases);
function showEventDetail(id) {
    const ev = calEvents.find(e => e.id == id);
    if (!ev) return;
    const fmt = v => v ? '\u20B1' + parseFloat(v).toLocaleString('en-US',{minimumFractionDigits:2}) : '—';
    const fmtDate = v => { if(!v) return '—'; try { return new Date(v).toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'}); } catch(e){ return v; } };
    document.getElementById('calModalTitle').textContent = ev.agent_name || '—';
    document.getElementById('calEventBody').innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            ${[
                ['Project', ev.project_name||'—', false],
                ['Client', ev.client_name||'—', false],
                ['Net TCP', fmt(ev.net_tcp), false],
                ['Commission', fmt(ev.commission), true],
                ['Date Released', fmtDate(ev.date_released), false],
                ['Status', ev.status||'—', false],
            ].map(([lbl,val,highlight]) => `
                <div style="background:#f8fafc;border-radius:8px;padding:10px 12px;border:1px solid #f1f5f9;">
                    <div style="font-size:9px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">${lbl}</div>
                    <div style="font-size:13px;font-weight:${highlight?'700':'600'};color:${highlight?'#059669':'#1e293b'};">${val}</div>
                </div>
            `).join('')}
        </div>`;
    document.getElementById('calEventModal').style.display = 'flex';
}
</script>
@endsection
