// Global Search — click navigates to page and highlights the matching row
(function () {
    'use strict';

    var initialized = false;

    // ── Row highlight (called on page load if sessionStorage has a pending highlight) ──
    function highlightRow(id) {
        if (!id) return;
        // Try by element id first (e.g. id="cm-5", id="expense-3")
        var el = document.getElementById(id);
        // Fallback: data-id attribute (strip prefix like "cd-", "trip-", etc.)
        if (!el) {
            var num = id.replace(/^[a-z]+-/i, '');
            el = document.querySelector('[data-id="' + num + '"]');
        }
        if (!el) return;

        // Scroll into view
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Flash highlight animation
        var orig = el.style.background || '';
        el.style.transition = 'background 0.3s';
        el.style.background = '#fef08a';
        el.style.outline    = '2px solid #f59e0b';
        el.style.outlineOffset = '-1px';

        // Pulse twice then fade out
        setTimeout(function () { el.style.background = orig || ''; el.style.outline = ''; }, 3500);
    }

    // Run highlight on every page load if there's a pending target
    (function checkPendingHighlight() {
        var pending = sessionStorage.getItem('gs_highlight');
        if (!pending) return;
        sessionStorage.removeItem('gs_highlight');
        // Wait for DOM + any lazy renders
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () { setTimeout(function () { highlightRow(pending); }, 400); });
        } else {
            setTimeout(function () { highlightRow(pending); }, 400);
        }
    })();

    // ── Search UI init ───────────────────────────────────────────────────
    function initSearch() {
        if (initialized) return;

        var searchToggle  = document.getElementById('searchToggle');
        var searchBar     = document.getElementById('searchBar');
        var searchInput   = document.getElementById('globalSearchInput');
        var searchResults = document.getElementById('searchResults');

        if (!searchToggle || !searchBar || !searchInput || !searchResults) return;
        initialized = true;

        // ── Icons ────────────────────────────────────────────────────────
        var icons = {
            home:     '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            building: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
            chart:    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            settings: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
            document: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            person:   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
            location: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
            calendar: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
            note:     '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>',
        };

        // badge: [bg, text, label]
        var badges = {
            commission: ['#dbeafe','#1e40af','COMMISSION'],
            client:     ['#ede9fe','#5b21b6','CLIENT'],
            trip:       ['#fef3c7','#92400e','SITE VISIT'],
            expense:    ['#fce7f3','#9d174d','EXPENSE'],
            report:     ['#d1fae5','#065f46','REPORT'],
            hrform:     ['#e0f2fe','#0369a1','HR FORM'],
            note:       ['#fef9c3','#713f12','NOTE'],
            contact:    ['#f0fdf4','#166534','CONTACT'],
            clientinfo: ['#ede9fe','#5b21b6','CLIENT INFO'],
            reserved:   ['#fef3c7','#92400e','RESERVED'],
            property:   ['#f1f5f9','#334155','PROPERTY'],
            agent:      ['#ecfdf5','#065f46','AGENT'],
            user:       ['#f0f9ff','#0c4a6e','USER'],
            department: ['#fff7ed','#9a3412','DEPT'],
            page:       ['#f1f5f9','#64748b','PAGE'],
        };

        var groupLabels = {
            commission: '📋 Commission Monitoring',
            client:     '👤 Client Database',
            trip:       '📍 Site Visit',
            expense:    '💰 Departmental Expenses',
            report:     '📊 Summary Reports',
            hrform:     '📝 HR Forms',
            note:       '🗒️ My Notes',
            contact:    '📞 Personnel Contacts',
            clientinfo: '👥 Client Info',
            reserved:   '🏠 Reserved Clients',
            property:   '🏢 Properties',
            agent:      '🤝 Sales Agents',
            user:       '👤 Users',
            department: '🏛️ Departments',
            page:       '🔗 Pages',
        };

        // ── Helpers ──────────────────────────────────────────────────────
        function escapeRe(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

        function hl(text, q) {
            if (!text) return '';
            try {
                return String(text).replace(new RegExp('(' + escapeRe(q) + ')', 'gi'),
                    '<mark style="background:#fef08a;color:#713f12;padding:0 2px;border-radius:2px;font-weight:700;">$1</mark>');
            } catch (e) { return String(text); }
        }

        function closeSearch() {
            searchBar.classList.remove('show');
            searchResults.classList.remove('show');
            searchResults.innerHTML = '';
        }

        // ── Toggle ───────────────────────────────────────────────────────
        searchToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (searchBar.classList.contains('show')) {
                closeSearch();
            } else {
                searchBar.classList.add('show');
                searchInput.focus();
                var np = document.getElementById('notificationPanel');
                if (np) np.classList.remove('show');
                var notes = document.getElementById('notesPanel');
                if (notes) notes.style.display = 'none';
            }
        });

        // ── Input ────────────────────────────────────────────────────────
        var debounce;
        searchInput.addEventListener('input', function (e) {
            clearTimeout(debounce);
            var q = e.target.value.trim();
            if (!q) { searchResults.classList.remove('show'); searchResults.innerHTML = ''; return; }
            debounce = setTimeout(function () { doSearch(q); }, 220);
        });

        // ── API call ─────────────────────────────────────────────────────
        function doSearch(q) {
            searchResults.innerHTML = '<div style="padding:16px;text-align:center;color:#6b7280;font-size:13px;">Searching…</div>';
            searchResults.classList.add('show');

            fetch('/api/global-search?q=' + encodeURIComponent(q))
                .then(function (r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(function (data) { render(data, q); })
                .catch(function () {
                    searchResults.innerHTML = '<div style="padding:16px;text-align:center;color:#ef4444;font-size:13px;font-weight:600;">Search error — please try again.</div>';
                });
        }

        // ── Render results ───────────────────────────────────────────────
        function render(items, q) {
            if (!items || !items.length) {
                searchResults.innerHTML =
                    '<div style="padding:28px;text-align:center;">' +
                    '<svg style="width:36px;height:36px;color:#d1d5db;margin:0 auto 8px;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>' +
                    '<div style="font-size:14px;font-weight:600;color:#374151;">No results found</div>' +
                    '<div style="font-size:12px;color:#9ca3af;margin-top:4px;">Try a different keyword</div></div>';
                searchResults.classList.add('show');
                return;
            }

        // Group by type — pages first, then data results
        var groups = {}, groupOrder = [];
        var pageItems = [], dataItems = [];
        items.forEach(function (item) {
            if (item.type === 'page') pageItems.push(item);
            else dataItems.push(item);
        });
        // Pages on top
        var sorted = pageItems.concat(dataItems);
        sorted.forEach(function (item) {
            var t = item.type || 'page';
            if (!groups[t]) { groups[t] = []; groupOrder.push(t); }
            groups[t].push(item);
        });

            var html = '';
            groupOrder.forEach(function (type) {
                var group = groups[type];
                html += '<div style="padding:5px 14px 2px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.8px;background:#f8fafc;border-bottom:1px solid #f1f5f9;">' +
                    (groupLabels[type] || type) + '</div>';

                group.forEach(function (item, idx) {
                    var bCfg   = badges[item.type] || badges.page;
                    var status = item.status ? item.status.toUpperCase() : bCfg[2];
                    var badge  = '<span style="background:' + bCfg[0] + ';color:' + bCfg[1] + ';padding:1px 6px;border-radius:4px;font-size:10px;font-weight:700;margin-left:6px;white-space:nowrap;">' + status + '</span>';
                    var iconSvg = icons[item.icon] || icons.document;
                    var amount  = item.amount ? '<span style="font-size:11px;color:#059669;font-weight:700;margin-left:6px;">' + item.amount + '</span>' : '';
                    var hasLink = item.highlight_id ? ' 🔗' : '';

                    html += '<div class="gs-item" data-type="' + type + '" data-idx="' + idx + '"' +
                        ' style="display:flex;align-items:center;gap:10px;padding:10px 14px;cursor:pointer;border-bottom:1px solid #f3f4f6;transition:background .12s;"' +
                        ' onmouseover="this.style.background=\'#eff6ff\'" onmouseout="this.style.background=\'\'">';
                    html += '<div style="width:30px;height:30px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">';
                    html += '<svg fill="none" stroke="#1e4575" viewBox="0 0 24 24" style="width:15px;height:15px;">' + iconSvg + '</svg></div>';
                    html += '<div style="flex:1;min-width:0;">';
                    html += '<div style="font-size:13px;font-weight:600;color:#111827;display:flex;align-items:center;flex-wrap:wrap;gap:2px;">' + hl(item.title, q) + badge + amount + '</div>';
                    html += '<div style="font-size:11px;color:#6b7280;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + hl(item.description, q) + '</div>';
                    html += '</div>';
                    // Arrow indicator if it will navigate to a specific row
                    if (item.highlight_id) {
                        html += '<div style="color:#1e4575;font-size:14px;flex-shrink:0;" title="Will highlight row">→</div>';
                    }
                    html += '</div>';
                });
            });

            searchResults.innerHTML = html;
            searchResults.classList.add('show');

            // ── Click: navigate + highlight ──────────────────────────────
            searchResults.querySelectorAll('.gs-item').forEach(function (el) {
                el.addEventListener('click', function () {
                    var type = this.getAttribute('data-type');
                    var idx  = parseInt(this.getAttribute('data-idx'));
                    var item = groups[type] && groups[type][idx];
                    if (!item) return;

                    closeSearch();
                    searchInput.value = '';

                    // Notes: open notes panel instead of navigating
                    if (item.url === '#notes') {
                        var notesPanel = document.getElementById('notesPanel');
                        if (notesPanel) notesPanel.style.display = 'block';
                        return;
                    }

                    var targetUrl  = item.url || '/';
                    var highlightId = item.highlight_id || null;
                    var currentPath = window.location.pathname;

                    if (highlightId) {
                        if (currentPath === targetUrl || currentPath.startsWith(targetUrl + '?')) {
                            // Already on the right page — just scroll & highlight
                            highlightRow(highlightId);
                        } else {
                            // Navigate to the page, highlight after load
                            sessionStorage.setItem('gs_highlight', highlightId);
                            window.location.href = targetUrl;
                        }
                    } else {
                        window.location.href = targetUrl;
                    }
                });
            });
        }

        // ── Close on outside click / Escape ─────────────────────────────
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.search-container')) closeSearch();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSearch();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
        initSearch();
    }
})();
