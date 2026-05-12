// ─────────────────────────────────────────────────────────────────────────────
// WHY: We import these packages that were installed via NPM (npm install ...).
// Vite will bundle all of these into one single JS file the browser downloads.
// This is how we use packages OFFLINE — no CDN links needed!
// ─────────────────────────────────────────────────────────────────────────────

// jQuery: DataTables requires jQuery to work
import $ from 'jquery';

// DataTables core + its default styling theme ('dt' stands for dataTables theme)
import DataTable from 'datatables.net-dt';

// DataTables Buttons extension: adds Export CSV / PDF button capability
import 'datatables.net-buttons-dt';

// The actual HTML5 export driver (handles the CSV and PDF file creation)
import 'datatables.net-buttons/js/buttons.html5.mjs';

// JSZip: Required by DataTables to package Excel/CSV files for download
import JSZip from 'jszip';

// pdfMake: The PDF engine that DataTables uses to generate PDF files
import pdfMake from 'pdfmake/build/pdfmake';

// pdfFonts: The font data that pdfMake needs to draw text inside PDF files
import pdfFonts from 'pdfmake/build/vfs_fonts';

// ─────────────────────────────────────────────────────────────────────────────
// WHY: pdfMake needs its font data attached before it can generate PDFs.
// This line connects the font file we imported above to the pdfMake engine.
// ─────────────────────────────────────────────────────────────────────────────
pdfMake.vfs = pdfFonts.vfs;

// ─────────────────────────────────────────────────────────────────────────────
// WHY: Vite loads app.js as a "module" — meaning it runs in its own private
// scope. Variables like $ and DataTable are NOT visible outside this file
// unless we explicitly attach them to the global `window` object.
// By doing window.JSZip = JSZip, DataTables can find JSZip when it needs it.
// ─────────────────────────────────────────────────────────────────────────────
window.JSZip = JSZip;
window.$ = window.jQuery = $;
window.DataTable = DataTable;

function escapeHtml(text) {
    if (text === null || text === undefined) {
        return '';
    }
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function recentTransactionTypeHtml(type) {
    const slug = type === 'PTF' ? 'ptf' : 'etf';
    return `<span class="tag tag-${slug}">${escapeHtml(type)}</span>`;
}

function recentTransactionStatusHtml(status) {
    const map = {
        settled: { cls: 'badge-green', label: 'Settled' },
        pending: { cls: 'badge-amber', label: 'Pending' },
        cancelled: { cls: 'badge-red', label: 'Cancelled' },
    };
    const row = map[status] || map.settled;
    return `<span class="badge ${row.cls}"><i class="fa-solid fa-circle" style="font-size:7px"></i> ${row.label}</span>`;
}

function etfFundRatingHtml(stars) {
    const n = Math.min(5, Math.max(0, parseInt(String(stars), 10) || 0));
    const gold = '★'.repeat(n);
    const gray = '★'.repeat(5 - n);

    return `<span style="color:#f59e0b">${gold}</span><span style="color:var(--text3)">${gray}</span>`;
}

// ─────────────────────────────────────────────────────────────────────────────
// CUSTOMER MANAGEMENT PAGE — DataTables (runs only if #customerTable exists)
// DASHBOARD — Recent Transactions — DataTables (runs only if #recentTransactionsTable exists)
//
// WHY this is here (not in the Blade file):
// Vite loads app.js as an ES Module which is always "deferred" by the browser.
// That means the browser FIRST finishes building the full HTML page, THEN runs
// this JS file. So by the time this code runs, the <table id="..."> is present.
// ─────────────────────────────────────────────────────────────────────────────

function initCustomerManagementDataTable() {
    const tableEl = document.getElementById('customerTable');
    if (!tableEl) {
        return;
    }

    if (tableEl.classList.contains('dataTable')) {
        return;
    }

    const mockCustomerData = [
        { id: 10, name: 'ABS',            email: 'N/A', phone: '0774003818', address: 'N/A', balance: '-Rs. 1,000', vehicles: '1 Vehicle(s)', last_transaction: '15 Apr 2026' },
        { id: 11, name: 'Nabeel un',      email: 'N/A', phone: '0772373304', address: 'N/A', balance: 'Rs. 0',      vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 12, name: 'Nabeel ko',      email: 'N/A', phone: '0771010775', address: 'N/A', balance: 'Rs. 0',      vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 13, name: 'Habeeb',         email: 'N/A', phone: '0751693833', address: 'N/A', balance: 'Rs. 0',      vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 14, name: 'Naseer tec',     email: 'N/A', phone: '0772382201', address: 'N/A', balance: 'Rs. 0',      vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 15, name: 'Nawaar',         email: 'N/A', phone: '0760123752', address: 'N/A', balance: 'Rs. 0',      vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 16, name: 'Nizar',          email: 'N/A', phone: '0773133009', address: 'N/A', balance: 'Rs. 200',    vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 17, name: 'Rimaas',         email: 'N/A', phone: '0771773401', address: 'N/A', balance: 'Rs. 0',      vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 18, name: 'Himy mow',       email: 'N/A', phone: '0777863787', address: 'N/A', balance: 'Rs. 0',      vehicles: 'No Tracking',  last_transaction: 'N/A' },
        { id: 19, name: 'Fawstheen Yaasir', email: 'N/A', phone: '0770279797', address: 'N/A', balance: 'Rs. 0',   vehicles: 'No Tracking',  last_transaction: 'N/A' },
    ];

    const table = new DataTable('#customerTable', {
        data: mockCustomerData,
        autoWidth: false,
        columns: [
            // Force string type so DataTables does not mark these as numeric/date.
            // Numeric/date headers use row-reverse in the default theme CSS, which
            // moves the sort arrows to the left of the label — visually like “extra”
            // arrows between columns when only some columns are numeric.
            { data: 'id', title: 'ID', type: 'string', width: '5%' },
            { data: 'name', title: 'Name', width: '12%' },
            { data: 'email', title: 'Email', width: '12%' },
            { data: 'phone', title: 'Phone', type: 'string', width: '10%' },
            { data: 'address', title: 'Address', width: '12%' },
            {
                data: 'balance',
                title: 'Outstanding Balance',
                width: '14%',
                render: function (data) {
                    const color = data.includes('-') ? '#3b82f6' : '#22c55e';
                    return `<span style="color:${color}; font-weight:500">${data}</span>`;
                }
            },
            { data: 'vehicles', title: 'Vehicles', width: '12%', render: function (data) { return `<span class="badge badge-blue">${data}</span>`; } },
            { data: 'last_transaction', title: 'Last Transaction', type: 'string', width: '13%' },
            {
                data: null,
                title: 'Actions',
                width: '10%',
                orderable: false,
                render: function () {
                    return `
                        <span class="dt-actions-cell">
                        <button type="button" class="btn btn-outline btn-sm btn-icon-pill" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn btn-outline btn-sm btn-icon-pill btn-outline--danger" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </span>
                    `;
                }
            }
        ],
        dom: 'rt<"dt-custom-footer"ip>',
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                className: 'dt-btn-csv',
            },
            {
                extend: 'pdfHtml5',
                text: 'Export PDF',
                className: 'dt-btn-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
            }
        ],
        ordering: true,
        paging: true,
        pageLength: 10,
        language: {
            info:          'Showing _START_ to _END_ of _TOTAL_ customers',
            infoEmpty:     'No customers found',
            paginate: {
                previous: '&lsaquo;',
                next:     '&rsaquo;',
            }
        },
        initComplete: function () {
            const count = this.api().data().length;
            const countEl = document.getElementById('customer-count');
            if (countEl) countEl.innerText = count;
        }
    });

    const searchBox = document.getElementById('custom-searchBox');
    if (searchBox) {
        searchBox.addEventListener('keyup', function () {
            table.search(this.value).draw();
        });
    }

    const csvBtn = document.getElementById('btn-export-csv');
    if (csvBtn) {
        csvBtn.addEventListener('click', function () {
            table.button('.dt-btn-csv').trigger();
        });
    }

    const pdfBtn = document.getElementById('btn-export-pdf');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', function () {
            table.button('.dt-btn-pdf').trigger();
        });
    }
}

function initRecentTransactionsDataTable() {
    const el = document.getElementById('recentTransactionsTable');
    if (!el) {
        return;
    }

    if (el.classList.contains('dataTable')) {
        return;
    }

    const mockRecentTransactions = [
        { date: '2026-05-02', fundName: 'Vanguard S&P 500', fundTicker: 'VOO', type: 'ETF', units: 120, price: '$512.40', total: '$61,488', status: 'settled' },
        { date: '2026-05-01', fundName: 'iShares Core MSCI', fundTicker: 'IEMG', type: 'ETF', units: 85, price: '$54.22', total: '$4,609', status: 'pending' },
        { date: '2026-04-30', fundName: 'Custom Growth Fund', fundTicker: 'CGF-A', type: 'PTF', units: 200, price: '$88.75', total: '$17,750', status: 'settled' },
        { date: '2026-04-29', fundName: 'SPDR Gold ETF', fundTicker: 'GLD', type: 'ETF', units: 40, price: '$224.10', total: '$8,964', status: 'cancelled' },
        { date: '2026-04-28', fundName: 'Tactical Bond PTF', fundTicker: 'TBP-2', type: 'PTF', units: 500, price: '$102.30', total: '$51,150', status: 'settled' },
    ];

    new DataTable('#recentTransactionsTable', {
        data: mockRecentTransactions,
        autoWidth: false,
        columns: [
            {
                data: 'date',
                title: 'Date',
                type: 'string',
                width: '11%',
                render: function (data) {
                    return `<span class="td-muted td-mono">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: null,
                title: 'Fund / Ticker',
                type: 'string',
                width: '24%',
                render: function (_data, _type, row) {
                    return `<div class="fund-name">${escapeHtml(row.fundName)}</div><div class="fund-ticker">${escapeHtml(row.fundTicker)}</div>`;
                },
            },
            {
                data: 'type',
                title: 'Type',
                type: 'string',
                width: '9%',
                render: function (data) {
                    return recentTransactionTypeHtml(data);
                },
            },
            {
                data: 'units',
                title: 'Units',
                type: 'string',
                width: '8%',
                render: function (data) {
                    return `<span class="td-mono">${escapeHtml(String(data))}</span>`;
                },
            },
            {
                data: 'price',
                title: 'Price',
                type: 'string',
                width: '10%',
                render: function (data) {
                    return `<span class="td-mono">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'total',
                title: 'Total',
                type: 'string',
                width: '11%',
                render: function (data) {
                    return `<span class="td-mono" style="font-weight:500">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'status',
                title: 'Status',
                type: 'string',
                width: '12%',
                render: function (data) {
                    return recentTransactionStatusHtml(data);
                },
            },
            {
                data: null,
                title: '',
                width: '15%',
                orderable: false,
                render: function () {
                    return '<button class="btn btn-outline btn-sm" onclick="showModal(\'trade-detail-modal\')">View</button>';
                },
            },
        ],
        dom: 'rt<"dt-custom-footer"ip>',
        ordering: true,
        paging: true,
        pageLength: 10,
        language: {
            info: 'Showing _START_ to _END_ of _TOTAL_ transactions',
            infoEmpty: 'No transactions found',
            paginate: {
                previous: '&lsaquo;',
                next: '&rsaquo;',
            },
        },
    });
}

function initEtfFundsDataTable() {
    const el = document.getElementById('etfFundsTable');
    if (!el) {
        return;
    }

    if (el.classList.contains('dataTable')) {
        return;
    }

    const rows =
        typeof globalThis.FUNDS !== 'undefined' && Array.isArray(globalThis.FUNDS) ? globalThis.FUNDS : [];

    new DataTable('#etfFundsTable', {
        data: rows,
        autoWidth: false,
        order: [[1, 'asc']],
        columns: [
            {
                data: null,
                title: '',
                width: '5%',
                className: 'dt-left',
                orderable: false,
                searchable: false,
                render: function () {
                    return '<input type="checkbox" aria-label="Select row">';
                },
            },
            {
                data: 'name',
                title: 'Fund Name',
                type: 'string',
                width: '22%',
                className: 'dt-left',
                render: function (data) {
                    return `<div class="fund-name">${escapeHtml(data)}</div>`;
                },
            },
            {
                data: 'ticker',
                title: 'Ticker',
                type: 'string',
                width: '8%',
                className: 'dt-left',
                render: function (data) {
                    return `<span class="fund-ticker">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'nav',
                title: 'NAV',
                type: 'string',
                width: '9%',
                className: 'dt-left',
                render: function (data) {
                    const n = parseFloat(data);
                    const s = Number.isFinite(n) ? n.toFixed(2) : escapeHtml(String(data));

                    return `<span class="td-mono">$${s}</span>`;
                },
            },
            {
                data: 'ret1',
                title: '1Y Return',
                type: 'string',
                width: '9%',
                className: 'dt-left',
                render: function (data) {
                    const v = parseFloat(data);
                    const cls = v >= 0 ? 'td-up' : 'td-down';
                    const sign = v >= 0 ? '+' : '';

                    return `<span class="${cls}">${sign}${escapeHtml(String(data))}%</span>`;
                },
            },
            {
                data: 'cagr',
                title: '3Y CAGR',
                type: 'string',
                width: '9%',
                className: 'dt-left',
                render: function (data) {
                    return `<span class="td-up">+${escapeHtml(String(data))}%</span>`;
                },
            },
            {
                data: 'aum',
                title: 'AUM',
                type: 'string',
                width: '10%',
                className: 'dt-left',
                render: function (data) {
                    return `<span class="td-mono">$${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'ter',
                title: 'TER',
                type: 'string',
                width: '8%',
                className: 'dt-left',
                render: function (data) {
                    return `<span class="td-mono">${escapeHtml(String(data))}%</span>`;
                },
            },
            {
                data: 'stars',
                title: 'Rating',
                type: 'string',
                width: '10%',
                className: 'dt-left',
                render: function (data) {
                    return etfFundRatingHtml(data);
                },
            },
            {
                data: null,
                title: '',
                width: '10%',
                className: 'dt-left',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    const payload = encodeURIComponent(
                        JSON.stringify({
                            name: row.name,
                            ticker: row.ticker,
                            nav: row.nav,
                            ret1: row.ret1,
                            cagr: row.cagr,
                            aum: row.aum,
                            ter: row.ter,
                            stars: row.stars,
                        })
                    );

                    return `<button type="button" class="btn btn-blue btn-sm js-open-add-etf-fund" data-fund="${payload}">Add</button>`;
                },
            },
        ],
        dom: 'rt<"dt-custom-footer"ip>',
        ordering: true,
        paging: true,
        pageLength: 10,
        language: {
            info: 'Showing _START_ to _END_ of _TOTAL_ funds',
            infoEmpty: 'No funds found',
            paginate: {
                previous: '&lsaquo;',
                next: '&rsaquo;',
            },
        },
        initComplete: function () {
            const n = this.api().rows().count();
            const badge = document.getElementById('etf-funds-count');
            if (badge) {
                badge.textContent = `${n} Funds`;
            }
        },
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initCustomerManagementDataTable();
    initRecentTransactionsDataTable();
    initEtfFundsDataTable();
});
