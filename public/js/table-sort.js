/**
 * table-sort.js
 * Adds Ascending / Descending sort buttons to the "#" (index) column
 * of every table on the site. Works automatically on any table whose
 * header row has a <th> containing exactly "#" — no per-page changes needed.
 * Handles tables whose rows are loaded later via AJAX (e.g. client-database).
 */
(function () {
    // Inject styles once
    if (!document.getElementById('idxSortStyles')) {
        var style = document.createElement('style');
        style.id = 'idxSortStyles';
        style.textContent = `
            .idx-sort-wrap{display:inline-flex;align-items:center;gap:4px;}
            .idx-sort-btn{
                display:inline-flex;align-items:center;justify-content:center;
                width:18px;height:18px;padding:0;margin:0;
                border:1px solid rgba(255,255,255,.5);
                background:rgba(255,255,255,.12);
                color:inherit;border-radius:4px;
                font-size:10px;line-height:1;cursor:pointer;
                transition:background .15s ease, transform .1s ease;
            }
            .idx-sort-btn:hover{background:rgba(255,255,255,.3);}
            .idx-sort-btn:active{transform:scale(.9);}
            .idx-sort-btn.active{background:#ffc93c;border-color:#ffc93c;color:#1e3a5f;font-weight:700;}
        `;
        document.head.appendChild(style);
    }

    function buildButtons(th) {
        var wrap = document.createElement('span');
        wrap.className = 'idx-sort-wrap';

        var label = document.createElement('span');
        label.textContent = th.textContent.trim();

        var ascBtn = document.createElement('button');
        ascBtn.type = 'button';
        ascBtn.className = 'idx-sort-btn idx-sort-asc';
        ascBtn.title = 'Sort ascending';
        ascBtn.innerHTML = '&#9650;'; // ▲

        var descBtn = document.createElement('button');
        descBtn.type = 'button';
        descBtn.className = 'idx-sort-btn idx-sort-desc';
        descBtn.title = 'Sort descending';
        descBtn.innerHTML = '&#9660;'; // ▼

        th.innerHTML = '';
        wrap.appendChild(label);
        wrap.appendChild(ascBtn);
        wrap.appendChild(descBtn);
        th.appendChild(wrap);

        return { ascBtn: ascBtn, descBtn: descBtn };
    }

    function sortTable(table, colIndex, direction, ascBtn, descBtn) {
        var tbody = table.querySelector('tbody');
        if (!tbody) return;

        var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'))
            .filter(function (r) { return r.children.length > colIndex; });
        if (!rows.length) return;

        rows.sort(function (a, b) {
            var aText = (a.children[colIndex].textContent || '').trim();
            var bText = (b.children[colIndex].textContent || '').trim();
            var aNum = parseFloat(aText.replace(/,/g, ''));
            var bNum = parseFloat(bText.replace(/,/g, ''));
            var cmp;
            if (!isNaN(aNum) && !isNaN(bNum)) {
                cmp = aNum - bNum;
            } else {
                cmp = aText.localeCompare(bText, undefined, { numeric: true, sensitivity: 'base' });
            }
            return direction === 'asc' ? cmp : -cmp;
        });

        var frag = document.createDocumentFragment();
        rows.forEach(function (r) { frag.appendChild(r); });
        tbody.appendChild(frag);

        ascBtn.classList.toggle('active', direction === 'asc');
        descBtn.classList.toggle('active', direction === 'desc');
    }

    function initTable(table) {
        if (table.dataset.idxSortInit) return;

        var headerRow = table.querySelector('thead tr');
        if (!headerRow) return;

        var ths = Array.prototype.slice.call(headerRow.querySelectorAll('th'));
        var colIndex = ths.findIndex(function (th) {
            return th.textContent.trim() === '#';
        });
        if (colIndex === -1) return;

        table.dataset.idxSortInit = '1';

        var th = ths[colIndex];
        var btns = buildButtons(th);

        btns.ascBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sortTable(table, colIndex, 'asc', btns.ascBtn, btns.descBtn);
        });
        btns.descBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sortTable(table, colIndex, 'desc', btns.ascBtn, btns.descBtn);
        });
    }

    function scanAllTables() {
        document.querySelectorAll('table').forEach(initTable);
    }

    document.addEventListener('DOMContentLoaded', scanAllTables);

    // Some tables (e.g. client-database) load their rows via AJAX after
    // page load, and their "#" header may appear slightly after DOMContentLoaded.
    // Watch for new tables/headers being added and wire them up too.
    var rescanTimer = null;
    var observer = new MutationObserver(function () {
        clearTimeout(rescanTimer);
        rescanTimer = setTimeout(scanAllTables, 200);
    });
    observer.observe(document.body, { childList: true, subtree: true });
})();