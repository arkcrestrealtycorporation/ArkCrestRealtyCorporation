@extends('layouts.dashboard')
@section('title', 'Practice History · Admin')

@section('content')
<style>
.pah-page { display:flex;flex-direction:column;gap:16px; }
.pah-topbar {
    background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);
    border-radius:20px;padding:32px 40px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;
    box-shadow:0 8px 32px rgba(30,69,117,.25);
}
.pah-title { font-size:22px;font-weight:700;color:white;margin:0 0 4px; }
.pah-sub { font-size:13px;color:rgba(255,255,255,.75);margin:0; }
.pah-back-btn { padding:8px 16px;background:rgba(255,255,255,.15);color:white;border:1.5px solid rgba(255,255,255,.3);border-radius:8px;font-size:12px;font-weight:600;text-decoration:none; }
.pah-back-btn:hover { background:rgba(255,255,255,.25); }

.pah-alert { background:#dcfce7;color:#166534;padding:12px 18px;border-radius:10px;font-size:13px;font-weight:600; }

.pah-table-wrap { background:white;border-radius:14px;border:1px solid #e8ecf0;box-shadow:0 2px 12px rgba(0,0,0,.05);overflow:hidden; }
table.pah-table { width:100%;border-collapse:collapse; }
.pah-table thead tr { background:linear-gradient(135deg,#0f2a4a,#1e4575); }
.pah-table th { padding:12px 18px;text-align:left;font-size:10px;font-weight:700;color:rgba(255,255,255,.85);text-transform:uppercase;letter-spacing:.6px; }
.pah-table td { padding:12px 18px;font-size:13px;color:#374151;border-bottom:1px solid #f1f5f9;vertical-align:middle; }
.pah-table tr:last-child td { border-bottom:none; }
.pah-table tr.clickable { cursor:pointer; }
.pah-table tr.clickable:hover td { background:#f8faff; }

.pah-badge { padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;display:inline-block; }
.pah-badge-sold { background:#dcfce7;color:#166534; }
.pah-badge-notsold { background:#fee2e2;color:#991b1b; }
.pah-badge-abandoned { background:#f1f5f9;color:#475569; }
.pah-badge-progress { background:#fef3c7;color:#92400e; }
.pah-diff { font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b; }
.pah-score { font-weight:700;color:#1e4575; }
.pah-empty { text-align:center;color:#94a3b8;font-size:13px;padding:40px; }
.pah-agent { font-weight:600;color:#1e293b; }

.pah-row-actions { display:flex;gap:8px;flex-wrap:wrap; }
.pah-icon-btn { border:1.5px solid #dbe4f0;background:#f8faff;color:#1e4575;font-size:11.5px;font-weight:700;padding:6px 12px;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:.4px; }
.pah-icon-btn:hover { background:#eef3ff; }
.pah-icon-btn:disabled { opacity:.4;cursor:not-allowed; }
.pah-icon-btn.danger { color:#dc2626;border-color:#fecaca;background:white; }
.pah-icon-btn.danger:hover { background:#fef2f2; }

.pah-check-col { width:36px;text-align:center; }
.pah-bulkbar { display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;padding:12px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc; }
.pah-bulkbar-count { font-size:12px;color:#64748b;font-weight:600; }
.pah-bulkbar-count strong { color:#0f172a; }
.pah-bulk-btn-delete { padding:8px 16px;border-radius:8px;font-size:12.5px;font-weight:700;cursor:pointer;border:none;background:#dc2626;color:white;white-space:nowrap; }
.pah-bulk-btn-delete:hover:not(:disabled) { background:#b91c1c; }
.pah-bulk-btn-delete:disabled { opacity:.45;cursor:not-allowed; }

.pah-pagination .pagination {
    list-style:none;display:flex;gap:6px;padding:0;margin:16px 0 0;align-items:center;flex-wrap:wrap;
}
.pah-pagination .page-item .page-link {
    display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;
    border:1px solid #e2e8f0;border-radius:8px;background:white;color:#1e4575;font-size:13px;font-weight:600;
    text-decoration:none;line-height:1;
}
.pah-pagination .page-item .page-link:hover { background:#f8faff;border-color:#c7d7f0; }
.pah-pagination .page-item.active .page-link { background:#1e4575;border-color:#1e4575;color:white; }
.pah-pagination .page-item.disabled .page-link { color:#cbd5e1;cursor:not-allowed;background:#f8fafc; }

/* Remarks modal — same look as the end-of-session scorecard popup */
.pah-score-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center; }
.pah-score-overlay.open { display:flex; }
.pah-score-box { background:white;border-radius:16px;width:480px;max-width:95vw;max-height:85vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2); }
.pah-score-hdr { padding:20px 24px;background:linear-gradient(135deg,#1e4575,#2563eb);color:white; }
.pah-score-outcome { font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;opacity:.85; }
.pah-score-overall { font-size:28px;font-weight:700;margin-top:4px; }
.pah-score-agent { font-size:12px;color:rgba(255,255,255,.75);margin-top:6px; }
.pah-score-body { padding:20px 24px;display:flex;flex-direction:column;gap:14px; }
.pah-score-rubric { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
.pah-score-metric { background:#f8fafc;border-radius:10px;padding:10px 12px;border:1px solid #f1f5f9; }
.pah-score-metric-lbl { font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.4px; }
.pah-score-metric-val { font-size:18px;font-weight:700;color:#1e4575; }
.pah-score-summary { font-size:13px;color:#374151;line-height:1.6; }
.pah-score-suggestions { list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:6px; }
.pah-score-suggestions li { font-size:12.5px;color:#374151;padding:8px 12px;background:#f8fafc;border-radius:8px;border-left:3px solid #2563eb; }
.pah-score-actions { padding:16px 24px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end; }
.pah-score-close {
    padding:10px 18px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;
    background:linear-gradient(135deg,#1e4575,#2563eb);color:white;
}

@media (max-width: 768px) {
    .pah-topbar { flex-direction:column;align-items:flex-start;gap:12px;padding:20px; }
    .pah-back-btn { width:100%;text-align:center;box-sizing:border-box; }
    .pah-table-wrap { overflow-x:auto !important; }
    table.pah-table { min-width:820px; }
}
</style>

<div class="pah-page">
    <div class="pah-topbar">
        <div>
            <h1 class="pah-title">Practice History</h1>
            <p class="pah-sub">Every agent's persuasion practice sessions, scores, and transcripts.</p>
        </div>
        <a href="{{ route('practice.admin') }}" class="pah-back-btn">Manage Scenarios</a>
    </div>



    <div class="pah-table-wrap">
        @if($sessions->isEmpty())
            <div class="pah-empty">No practice sessions yet.</div>
        @else
            <div class="pah-bulkbar">
                <div class="pah-bulkbar-count"><strong id="pahSelCount">0</strong> selected</div>
                <button type="button" id="pahBulkDeleteBtn" class="pah-bulk-btn-delete" disabled onclick="pahBulkDelete()">Delete Selected</button>
            </div>
            <table class="pah-table">
                <thead>
                    <tr>
                        <th class="pah-check-col"><input type="checkbox" id="pahSelectAll" onchange="pahToggleSelectAll(this)"></th>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Buyer</th>
                        <th>Difficulty</th>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $s)
                        @php
                            $badgeClass = match($s->status) {
                                'SOLD' => 'pah-badge-sold',
                                'NOT_SOLD' => 'pah-badge-notsold',
                                'ABANDONED' => 'pah-badge-abandoned',
                                default => 'pah-badge-progress',
                            };
                            $statusLabel = ucfirst(strtolower(str_replace('_', ' ', $s->status)));
                        @endphp
                        <tr class="clickable" data-search="{{ strtolower(($s->user->name ?? '').' '.($s->scenario->buyer_name ?? '').' '.($s->scenario->name ?? '')) }}" onclick="window.location='{{ route('practice.chat', $s) }}'">
                            <td class="pah-check-col" onclick="event.stopPropagation()"><input type="checkbox" class="pah-row-check" value="{{ $s->id }}" onchange="pahUpdateSelection()"></td>
                            <td>{{ $s->created_at->format('M d, Y g:ia') }}</td>
                            <td class="pah-agent">{{ $s->user->name ?? '—' }}</td>
                            <td>{{ $s->scenario->buyer_name ?? '—' }} <span style="color:#94a3b8;">({{ $s->scenario->name ?? '—' }})</span></td>
                            <td><span class="pah-diff">{{ ucfirst(strtolower($s->difficulty)) }}</span></td>
                            <td><span class="pah-badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                            <td><span class="pah-score">{{ $s->overall_score !== null ? $s->overall_score.'/100' : '—' }}</span></td>
                            <td>
                                @if($s->scorecard)
                                    <button type="button" class="pah-icon-btn"
                                        onclick="event.stopPropagation(); pahShowRemarksById({{ $s->id }})">
                                        View Remarks
                                    </button>
                                @else
                                    <button type="button" class="pah-icon-btn" disabled>No Remarks</button>
                                @endif
                            </td>
                            <td>
                                <div class="pah-row-actions">
                                    <a href="{{ route('practice.chat', $s) }}" class="pah-icon-btn" onclick="event.stopPropagation()">View Chat</a>
                                    <form method="POST" action="{{ route('practice.admin.history.destroy', $s) }}"
                                        onclick="event.stopPropagation()"
                                        onsubmit="return confirm('Delete this practice session for {{ $s->user->name ?? 'this agent' }}? This cannot be undone from here.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pah-icon-btn danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if($sessions->hasPages())
        <div class="pah-pagination">{{ $sessions->links('pagination::bootstrap-4') }}</div>
    @endif
</div>

<script id="pahScorecardsData" type="application/json">
{!! json_encode(
    $sessions->mapWithKeys(fn ($s) => [$s->id => [
        'status'   => $s->status,
        'agent'    => $s->user->name ?? '—',
        'scorecard' => $s->scorecard,
    ]]),
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
) !!}
</script>

<!-- Remarks modal -->
<div class="pah-score-overlay" id="pahRemarksOverlay" onclick="if(event.target===this) pahCloseRemarks()">
    <div class="pah-score-box">
        <div class="pah-score-hdr">
            <div class="pah-score-outcome" id="pahRemarksOutcome"></div>
            <div class="pah-score-overall" id="pahRemarksOverall"></div>
            <div class="pah-score-agent" id="pahRemarksAgent"></div>
        </div>
        <div class="pah-score-body">
            <div class="pah-score-rubric">
                <div class="pah-score-metric">
                    <div class="pah-score-metric-lbl">Rapport</div>
                    <div class="pah-score-metric-val" id="pahRemarksRapport">—</div>
                </div>
                <div class="pah-score-metric">
                    <div class="pah-score-metric-lbl">Objection Handling</div>
                    <div class="pah-score-metric-val" id="pahRemarksObjection">—</div>
                </div>
                <div class="pah-score-metric">
                    <div class="pah-score-metric-lbl">Product Knowledge</div>
                    <div class="pah-score-metric-val" id="pahRemarksProduct">—</div>
                </div>
                <div class="pah-score-metric">
                    <div class="pah-score-metric-lbl">Closing Technique</div>
                    <div class="pah-score-metric-val" id="pahRemarksClosing">—</div>
                </div>
            </div>
            <div class="pah-score-summary" id="pahRemarksSummary"></div>
            <ul class="pah-score-suggestions" id="pahRemarksSuggestions"></ul>
        </div>
        <div class="pah-score-actions">
            <button type="button" class="pah-score-close" onclick="pahCloseRemarks()">Close</button>
        </div>
    </div>
</div>

<script>
var pahScorecardsMap = JSON.parse(document.getElementById('pahScorecardsData').textContent || '{}');

function pahShowRemarksById(sessionId) {
    var entry = pahScorecardsMap[sessionId] || {};
    var status = entry.status;
    var scorecard = entry.scorecard || {};
    var outcomeLabel = status === 'SOLD' ? 'Sold! 🎉' : (status === 'ABANDONED' ? 'Ended Early' : (status === 'IN_PROGRESS' ? 'In Progress' : 'Not Sold'));

    document.getElementById('pahRemarksOutcome').textContent = outcomeLabel;
    document.getElementById('pahRemarksOverall').textContent = (scorecard.overall_score ?? '—') + (scorecard.overall_score != null ? '/100' : '');
    document.getElementById('pahRemarksAgent').textContent = 'Agent: ' + (entry.agent || '—');
    document.getElementById('pahRemarksRapport').textContent = scorecard.rapport ?? '—';
    document.getElementById('pahRemarksObjection').textContent = scorecard.objection_handling ?? '—';
    document.getElementById('pahRemarksProduct').textContent = scorecard.product_knowledge ?? '—';
    document.getElementById('pahRemarksClosing').textContent = scorecard.closing_technique ?? '—';
    document.getElementById('pahRemarksSummary').textContent = scorecard.summary || 'No summary available.';

    var list = document.getElementById('pahRemarksSuggestions');
    var suggestions = scorecard.suggestions || [];
    if (suggestions.length) {
        list.innerHTML = '';
        suggestions.forEach(function (s) {
            var li = document.createElement('li');
            li.textContent = s;
            list.appendChild(li);
        });
    } else {
        list.innerHTML = '<li>No specific suggestions this time.</li>';
    }

    document.getElementById('pahRemarksOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function pahCloseRemarks() {
    document.getElementById('pahRemarksOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') pahCloseRemarks();
});

function pahRowChecks() {
    return Array.prototype.slice.call(document.querySelectorAll('.pah-row-check'));
}

function pahUpdateSelection() {
    var checks = pahRowChecks();
    var checked = checks.filter(function (c) { return c.checked; });

    document.getElementById('pahSelCount').textContent = checked.length;
    document.getElementById('pahBulkDeleteBtn').disabled = checked.length === 0;

    var selectAll = document.getElementById('pahSelectAll');
    if (selectAll) {
        selectAll.checked = checks.length > 0 && checked.length === checks.length;
        selectAll.indeterminate = checked.length > 0 && checked.length < checks.length;
    }
}

function pahToggleSelectAll(source) {
    pahRowChecks().forEach(function (c) { c.checked = source.checked; });
    pahUpdateSelection();
}

function pahBulkDelete() {
    var ids = pahRowChecks().filter(function (c) { return c.checked; }).map(function (c) { return c.value; });
    if (!ids.length) return;

    showConfirm(
        'Delete ' + ids.length + ' selected practice session(s)? This cannot be undone from here.',
        function () {
            var btn = document.getElementById('pahBulkDeleteBtn');
            btn.disabled = true;
            btn.textContent = 'Deleting…';

            fetch(@json(route('practice.admin.history.bulk-destroy')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ ids: ids }),
            })
                .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
                .then(function (result) {
                    if (!result.ok) {
                        showToast(result.data.message || 'Could not delete the selected sessions.', 'error', 'Delete Failed');
                        btn.disabled = false;
                        btn.textContent = 'Delete Selected';
                        return;
                    }
                    showToast(result.data.message, 'success', 'Deleted');
                    setTimeout(function () { window.location.reload(); }, 900);
                })
                .catch(function () {
                    showToast('Could not reach the server — please try again.', 'error', 'Delete Failed');
                    btn.disabled = false;
                    btn.textContent = 'Delete Selected';
                });
        },
        'Delete selected sessions?'
    );
}
</script>
@endsection