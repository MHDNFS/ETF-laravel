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

// Tom Select: searchable single-select for all <x-forms.form-select> (.select2-custom) fields
import TomSelect from 'tom-select';

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

/**
 * Initialize Tom Select on every `.select2-custom` wrapper from `<x-forms.form-select>` (search in dropdown).
 * Opt out per field: `<x-forms.form-select :searchable="false">`.
 */
function initSearchableFormSelects(scope = document) {
    scope.querySelectorAll('.select2-custom').forEach((wrap) => {
        if (wrap.getAttribute('data-searchable') === 'false') {
            return;
        }
        const el = wrap.querySelector('select');
        if (!el || el.tomselect) {
            return;
        }
        if (el.disabled && el.options.length === 0) {
            return;
        }

        new TomSelect(el, {
            plugins: ['dropdown_input'],
            allowEmptyOption: true,
            create: false,
            maxOptions: null,
            /**
             * Keep dropdown under the control. Do not use `document.body` here: ancestors such as
             * `.page-animate` use `transform`, which breaks Tom Select’s coordinates and sends the menu
             * to the wrong corner. Clipping is prevented by `.etf-funds-filters-card { overflow: visible }`
             * in resources/css/app.css.
             */
            dropdownParent: wrap,
        });
    });
}

window.initSearchableFormSelects = initSearchableFormSelects;

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

/** Sync native <select> value when Tom Select (x-forms.form-select) is active. */
function setSelectElementValue(selectEl, value) {
    if (!selectEl) {
        return;
    }
    const str = value != null && value !== '' ? String(value) : '';
    if (selectEl.tomselect) {
        selectEl.tomselect.setValue(str, true);
        return;
    }
    selectEl.value = str;
}

/** Read value from native or Tom Select–enhanced <select>. */
function getSelectElementValue(selectEl) {
    if (!selectEl) {
        return '';
    }
    if (selectEl.tomselect) {
        const v = selectEl.tomselect.getValue();
        if (Array.isArray(v)) {
            return v.join(',');
        }

        return v != null ? String(v) : '';
    }

    return selectEl.value ?? '';
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

/** Live reference for customer DataTable (add/edit modals on customer-management page). */
let customerManagementDataTable = null;
/** Dashboard `#recentTransactionsTable` — export toolbar targets this instance. */
let recentTransactionsDataTable = null;

const DASHBOARD_TX_MOBILE_BP = 700;

/** Hide Units + Price on dashboard transactions table when viewport is narrow. */
function applyDashboardRecentTxColumns(table) {
    if (!table || !document.getElementById('recentTransactionsTable')) {
        return;
    }

    const compact = window.innerWidth < DASHBOARD_TX_MOBILE_BP;
    table.column(3).visible(!compact);
    table.column(4).visible(!compact);
    table.columns.adjust().draw(false);
}

let dashboardRecentTxResizeTimer = null;

function bindDashboardRecentTxResize(table) {
    window.addEventListener('resize', function () {
        clearTimeout(dashboardRecentTxResizeTimer);
        dashboardRecentTxResizeTimer = setTimeout(function () {
            applyDashboardRecentTxColumns(table);
        }, 150);
    });
}

const ETF_FUNDS_MOBILE_BP = 700;
const ETF_FUNDS_MOBILE_BP_SM = 520;

/** Hide non-essential ETF fund columns on narrow viewports. */
function applyEtfFundsMobileColumns(table) {
    if (!table || !document.getElementById('etfFundsTable')) {
        return;
    }

    const compact = window.innerWidth < ETF_FUNDS_MOBILE_BP;
    const extraCompact = window.innerWidth < ETF_FUNDS_MOBILE_BP_SM;

    table.column(0).visible(!compact);
    table.column(3).visible(!extraCompact);
    table.column(5).visible(!compact);
    table.column(6).visible(!compact);
    table.column(7).visible(!compact);
    table.column(8).visible(!extraCompact);
    table.columns.adjust().draw(false);
}

let etfFundsResizeTimer = null;

function bindEtfFundsResize(table) {
    window.addEventListener('resize', function () {
        clearTimeout(etfFundsResizeTimer);
        etfFundsResizeTimer = setTimeout(function () {
            applyEtfFundsMobileColumns(table);
        }, 150);
    });
}

const CUSTOMER_MGMT_MOBILE_BP = 700;
const CUSTOMER_MGMT_MOBILE_BP_SM = 520;

/** Hide non-essential customer columns on narrow viewports. */
function applyCustomerManagementMobileColumns(table) {
    if (!table || !document.getElementById('customerTable')) {
        return;
    }

    const compact = window.innerWidth < CUSTOMER_MGMT_MOBILE_BP;
    const extraCompact = window.innerWidth < CUSTOMER_MGMT_MOBILE_BP_SM;

    table.column(0).visible(!compact);
    table.column(4).visible(!compact);
    table.column(6).visible(!compact);
    table.column(7).visible(!compact);
    table.column(3).visible(!extraCompact);
    table.column(5).visible(!extraCompact);
    table.columns.adjust().draw(false);
}

let customerMgmtResizeTimer = null;

function bindCustomerManagementResize(table) {
    window.addEventListener('resize', function () {
        clearTimeout(customerMgmtResizeTimer);
        customerMgmtResizeTimer = setTimeout(function () {
            applyCustomerManagementMobileColumns(table);
        }, 150);
    });
}

const PTF_PORTFOLIO_MOBILE_BP = 700;
const PTF_PORTFOLIO_MOBILE_BP_SM = 520;

/** Hide non-essential PTF holdings columns on narrow viewports. */
function applyPtfPortfolioMobileColumns(table) {
    if (!table || !document.getElementById('ptfHoldingsTable')) {
        return;
    }

    const compact = window.innerWidth < PTF_PORTFOLIO_MOBILE_BP;
    const extraCompact = window.innerWidth < PTF_PORTFOLIO_MOBILE_BP_SM;

    table.column(2).visible(!compact);
    table.column(3).visible(!compact);
    table.column(4).visible(!compact);
    table.column(7).visible(!compact);
    table.column(1).visible(!extraCompact);
    table.columns.adjust().draw(false);
}

let ptfPortfolioResizeTimer = null;

function bindPtfPortfolioResize(table) {
    window.addEventListener('resize', function () {
        clearTimeout(ptfPortfolioResizeTimer);
        ptfPortfolioResizeTimer = setTimeout(function () {
            applyPtfPortfolioMobileColumns(table);
        }, 150);
    });
}

function initCustomerManagementDataTable() {
    const tableEl = document.getElementById('customerTable');
    if (!tableEl) {
        return;
    }

    if (tableEl.classList.contains('dataTable')) {
        return;
    }

    let editingCustomerId = null;

    function setCustomerField(id, value) {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }
        el.value = value != null && value !== '' ? String(value) : '';
    }

    function emptyCustomerFieldToNa(value) {
        const t = String(value ?? '').trim();

        return t === '' ? 'N/A' : t;
    }

    function resetAddCustomerForm() {
        setCustomerField('add-customer-name', '');
        setCustomerField('add-customer-email', '');
        setCustomerField('add-customer-phone', '');
        setCustomerField('add-customer-address', '');
        setCustomerField('add-customer-balance', 'Rs. 0');
        setCustomerField('add-customer-last-transaction', 'N/A');
        const vehiclesEl = document.getElementById('add-customer-vehicles');
        if (vehiclesEl) {
            setSelectElementValue(vehiclesEl, 'No Tracking');
        }
    }

    function readAddCustomerForm() {
        const name = document.getElementById('add-customer-name')?.value.trim() ?? '';
        const emailRaw = document.getElementById('add-customer-email')?.value ?? '';
        const phoneRaw = document.getElementById('add-customer-phone')?.value.trim() ?? '';
        const address = emptyCustomerFieldToNa(document.getElementById('add-customer-address')?.value ?? '');
        const balanceRaw = document.getElementById('add-customer-balance')?.value.trim() ?? '';
        const lastTxRaw = document.getElementById('add-customer-last-transaction')?.value.trim() ?? '';
        const vehicles = getSelectElementValue(document.getElementById('add-customer-vehicles')) || 'No Tracking';

        return {
            name,
            email: emptyCustomerFieldToNa(emailRaw),
            phone: phoneRaw === '' ? 'N/A' : phoneRaw,
            address,
            balance: balanceRaw === '' ? 'Rs. 0' : balanceRaw,
            last_transaction: lastTxRaw === '' ? 'N/A' : lastTxRaw,
            vehicles,
        };
    }

    function readEditCustomerForm() {
        const id = document.getElementById('edit-customer-id')?.value ?? '';
        const name = document.getElementById('edit-customer-name')?.value.trim() ?? '';
        const emailRaw = document.getElementById('edit-customer-email')?.value ?? '';
        const phoneRaw = document.getElementById('edit-customer-phone')?.value.trim() ?? '';
        const address = emptyCustomerFieldToNa(document.getElementById('edit-customer-address')?.value ?? '');
        const balanceRaw = document.getElementById('edit-customer-balance')?.value.trim() ?? '';
        const lastTxRaw = document.getElementById('edit-customer-last-transaction')?.value.trim() ?? '';
        const vehicles = getSelectElementValue(document.getElementById('edit-customer-vehicles')) || 'No Tracking';

        return {
            id,
            name,
            email: emptyCustomerFieldToNa(emailRaw),
            phone: phoneRaw === '' ? 'N/A' : phoneRaw,
            address,
            balance: balanceRaw === '' ? 'Rs. 0' : balanceRaw,
            last_transaction: lastTxRaw === '' ? 'N/A' : lastTxRaw,
            vehicles,
        };
    }

    function fillEditCustomerForm(row) {
        setCustomerField('edit-customer-id', row.id);
        setCustomerField('edit-customer-name', row.name);
        setCustomerField('edit-customer-email', row.email === 'N/A' ? '' : row.email);
        setCustomerField('edit-customer-phone', row.phone === 'N/A' ? '' : row.phone);
        setCustomerField('edit-customer-address', row.address === 'N/A' ? '' : row.address);
        setCustomerField('edit-customer-balance', row.balance);
        setCustomerField('edit-customer-last-transaction', row.last_transaction === 'N/A' ? '' : row.last_transaction);
        const vehiclesEl = document.getElementById('edit-customer-vehicles');
        if (vehiclesEl) {
            const v = row.vehicles || 'No Tracking';
            const use = [...vehiclesEl.options].some((o) => o.value === v) ? v : 'No Tracking';
            setSelectElementValue(vehiclesEl, use);
        }
    }

    function findCustomerRowApiById(tableApi, id) {
        let found = null;
        tableApi.rows().every(function () {
            if (String(this.data().id) === String(id)) {
                found = this;
                return false;
            }
            return true;
        });
        return found;
    }

    function nextCustomerId(tableApi) {
        let max = 0;
        tableApi.rows().every(function () {
            const n = parseInt(String(this.data().id), 10);
            if (!Number.isNaN(n) && n > max) {
                max = n;
            }
        });
        return max + 1;
    }

    function refreshCustomerCountBadge(tableApi) {
        const countEl = document.getElementById('customer-count');
        if (countEl) {
            countEl.textContent = String(tableApi.rows().count());
        }
    }

    /**
     * Build the "Columns" dropdown: toggle DataTables visibility for every column except the last (Actions).
     */
    function setupCustomerTableColumnMenu(tableApi, tableElement) {
        const wrap = document.getElementById('customer-columns-dropdown');
        const toggle = document.getElementById('customer-columns-toggle');
        const menu = document.getElementById('customer-columns-menu');
        const list = document.getElementById('customer-columns-checkboxes');
        const chevron = document.getElementById('customer-columns-chevron');

        if (!wrap || !toggle || !menu || !list || !tableApi || !tableElement) {
            return;
        }

        const headerCells = tableElement.querySelectorAll('thead th');
        const colCount = headerCells.length;
        if (colCount < 2) {
            return;
        }

        const lastDataColIndex = colCount - 2;

        function columnLabel(idx) {
            const cell = headerCells[idx];
            const raw = cell?.textContent ?? '';

            return raw.replace(/\s+/g, ' ').trim() || `Column ${idx}`;
        }

        list.innerHTML = '';

        for (let i = 0; i <= lastDataColIndex; i++) {
            const title = columnLabel(i);
            const row = document.createElement('div');
            row.className = 'customer-columns-menu__row';
            row.setAttribute('role', 'menuitemcheckbox');

            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.id = `customer-col-vis-${i}`;
            cb.checked = tableApi.column(i).visible();

            const label = document.createElement('label');
            label.htmlFor = cb.id;
            label.textContent = title;

            cb.addEventListener('change', () => {
                if (!cb.checked) {
                    let otherChecked = 0;
                    for (let j = 0; j <= lastDataColIndex; j++) {
                        const el = list.querySelector(`#customer-col-vis-${j}`);
                        if (el && el !== cb && el.checked) {
                            otherChecked++;
                        }
                    }
                    if (otherChecked === 0) {
                        cb.checked = true;
                        showToast('info', 'Columns', 'Keep at least one column visible.');
                        return;
                    }
                }
                tableApi.column(i).visible(cb.checked);
            });

            row.appendChild(cb);
            row.appendChild(label);
            list.appendChild(row);
        }

        function setOpen(open) {
            menu.hidden = !open;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (chevron) {
                chevron.classList.toggle('fa-chevron-up', open);
                chevron.classList.toggle('fa-chevron-down', !open);
            }
        }

        setOpen(false);

        toggle.addEventListener('click', (ev) => {
            ev.stopPropagation();
            setOpen(menu.hidden);
        });

        document.addEventListener('click', (ev) => {
            if (menu.hidden) {
                return;
            }
            if (!wrap.contains(ev.target)) {
                setOpen(false);
            }
        });
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
                        <button type="button" class="btn btn-outline btn-sm btn-icon-pill js-edit-customer" title="Edit">
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
            const api = this.api();
            refreshCustomerCountBadge(api);
            setupCustomerTableColumnMenu(api, tableEl);
        }
    });

    customerManagementDataTable = table;
    applyCustomerManagementMobileColumns(table);
    bindCustomerManagementResize(table);

    const addCustomerBtn = document.getElementById('btn-add-customer');
    if (addCustomerBtn) {
        addCustomerBtn.addEventListener('click', () => {
            resetAddCustomerForm();
            showModal('add-customer-modal');
        });
    }

    const addCustomerSave = document.getElementById('add-customer-save');
    if (addCustomerSave) {
        addCustomerSave.addEventListener('click', () => {
            const f = readAddCustomerForm();
            if (!f.name) {
                showToast('error', 'Name required', 'Please enter a customer name.');
                return;
            }
            table.row
                .add({
                    id: nextCustomerId(table),
                    name: f.name,
                    email: f.email,
                    phone: f.phone,
                    address: f.address,
                    balance: f.balance,
                    vehicles: f.vehicles,
                    last_transaction: f.last_transaction,
                })
                .draw(false);
            refreshCustomerCountBadge(table);
            closeModal('add-customer-modal');
            showToast('success', 'Customer added', 'New customer added to the table (demo).');
        });
    }

    const editCustomerSave = document.getElementById('edit-customer-save');
    if (editCustomerSave) {
        editCustomerSave.addEventListener('click', () => {
            if (editingCustomerId === null) {
                return;
            }
            const f = readEditCustomerForm();
            if (!f.name) {
                showToast('error', 'Name required', 'Please enter a customer name.');
                return;
            }
            const rowApi = findCustomerRowApiById(table, editingCustomerId);
            if (!rowApi) {
                showToast('error', 'Row not found', 'Could not update this customer.');
                return;
            }
            rowApi
                .data({
                    id: f.id,
                    name: f.name,
                    email: f.email,
                    phone: f.phone,
                    address: f.address,
                    balance: f.balance,
                    vehicles: f.vehicles,
                    last_transaction: f.last_transaction,
                })
                .draw(false);
            editingCustomerId = null;
            closeModal('edit-customer-modal');
            showToast('success', 'Customer updated', 'Changes saved (demo).');
        });
    }

    tableEl.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.js-edit-customer');
        if (!editBtn || !tableEl.contains(editBtn)) {
            return;
        }
        const tr = editBtn.closest('tr');
        const rowApi = table.row(tr);
        if (!rowApi.length) {
            return;
        }
        const rowData = rowApi.data();
        editingCustomerId = rowData.id;
        fillEditCustomerForm(rowData);
        showModal('edit-customer-modal');
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

    const bulkUploadBtn = document.getElementById('btn-bulk-upload-customers');
    const bulkModalId = 'bulk-upload-customers-modal';
    const bulkFileInput = document.getElementById('bulk-upload-file-input');
    const bulkFolderInput = document.getElementById('bulk-upload-folder-input');
    const bulkPickFiles = document.getElementById('bulk-upload-pick-files');
    const bulkPickFolder = document.getElementById('bulk-upload-pick-folder');
    const bulkListEl = document.getElementById('bulk-upload-file-list');
    const bulkCountEl = document.getElementById('bulk-upload-count');
    const bulkEmptyHint = document.getElementById('bulk-upload-empty-hint');
    const bulkSubmit = document.getElementById('bulk-upload-submit');
    const bulkClearBtn = document.getElementById('bulk-upload-clear');

    /** @type {File[]} */
    let bulkFiles = [];

    function bulkFileKey(f) {
        const rel = f.webkitRelativePath || f.name;

        return `${rel}|${f.size}|${f.lastModified}`;
    }

    function renderBulkList() {
        if (!bulkListEl || !bulkCountEl || !bulkEmptyHint || !bulkSubmit) {
            return;
        }
        bulkCountEl.textContent = String(bulkFiles.length);
        bulkEmptyHint.style.display = bulkFiles.length ? 'none' : 'block';
        if (bulkClearBtn) {
            bulkClearBtn.style.display = bulkFiles.length ? 'inline-flex' : 'none';
        }
        bulkListEl.innerHTML = '';
        bulkFiles.slice(0, 80).forEach((f) => {
            const li = document.createElement('li');
            const label = f.webkitRelativePath || f.name;
            li.textContent = `${label} (${(f.size / 1024).toFixed(1)} KB)`;
            bulkListEl.appendChild(li);
        });
        if (bulkFiles.length > 80) {
            const li = document.createElement('li');
            li.style.color = 'var(--text3)';
            li.textContent = `… and ${bulkFiles.length - 80} more`;
            bulkListEl.appendChild(li);
        }
        bulkSubmit.disabled = bulkFiles.length === 0;
    }

    function addBulkFiles(fileList) {
        const seen = new Set(bulkFiles.map(bulkFileKey));
        for (let i = 0; i < fileList.length; i++) {
            const f = fileList.item(i);
            if (!f) {
                continue;
            }
            const k = bulkFileKey(f);
            if (!seen.has(k)) {
                seen.add(k);
                bulkFiles.push(f);
            }
        }
        renderBulkList();
    }

    function resetBulkUploadUi() {
        bulkFiles = [];
        if (bulkFileInput) {
            bulkFileInput.value = '';
        }
        if (bulkFolderInput) {
            bulkFolderInput.value = '';
        }
        renderBulkList();
    }

    if (bulkUploadBtn) {
        bulkUploadBtn.addEventListener('click', () => {
            resetBulkUploadUi();
            showModal(bulkModalId);
        });
    }

    if (bulkPickFiles && bulkFileInput) {
        bulkPickFiles.addEventListener('click', () => bulkFileInput.click());
    }
    if (bulkPickFolder && bulkFolderInput) {
        bulkPickFolder.addEventListener('click', () => bulkFolderInput.click());
    }

    if (bulkFileInput) {
        bulkFileInput.addEventListener('change', () => {
            if (bulkFileInput.files?.length) {
                addBulkFiles(bulkFileInput.files);
            }
            bulkFileInput.value = '';
        });
    }
    if (bulkFolderInput) {
        bulkFolderInput.addEventListener('change', () => {
            if (bulkFolderInput.files?.length) {
                addBulkFiles(bulkFolderInput.files);
            }
            bulkFolderInput.value = '';
        });
    }

    if (bulkClearBtn) {
        bulkClearBtn.addEventListener('click', () => {
            bulkFiles = [];
            renderBulkList();
        });
    }

    if (bulkSubmit) {
        bulkSubmit.addEventListener('click', () => {
            if (!bulkFiles.length) {
                return;
            }
            closeModal(bulkModalId);
            showToast(
                'success',
                'Upload queued',
                `Prepared ${bulkFiles.length} file(s) for upload (demo — add a Laravel route and FormData.post() to store or import).`
            );
            resetBulkUploadUi();
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

    const recentTxTable = new DataTable('#recentTransactionsTable', {
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
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                className: 'dt-recent-tx-csv',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
            },
            {
                extend: 'pdfHtml5',
                text: 'Export PDF',
                className: 'dt-recent-tx-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
            },
        ],
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

    recentTransactionsDataTable = recentTxTable;
    applyDashboardRecentTxColumns(recentTxTable);
    bindDashboardRecentTxResize(recentTxTable);

    const recentCsvBtn = document.getElementById('btn-recent-tx-export-csv');
    if (recentCsvBtn) {
        recentCsvBtn.addEventListener('click', function () {
            recentTxTable.button('.dt-recent-tx-csv').trigger();
        });
    }
    const recentPdfBtn = document.getElementById('btn-recent-tx-export-pdf');
    if (recentPdfBtn) {
        recentPdfBtn.addEventListener('click', function () {
            recentTxTable.button('.dt-recent-tx-pdf').trigger();
        });
    }
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

    const etfFundsTable = new DataTable('#etfFundsTable', {
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
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                className: 'dt-etf-funds-csv',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] },
            },
            {
                extend: 'pdfHtml5',
                text: 'Export PDF',
                className: 'dt-etf-funds-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] },
            },
        ],
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

    applyEtfFundsMobileColumns(etfFundsTable);
    bindEtfFundsResize(etfFundsTable);

    const etfCsvBtn = document.getElementById('btn-etf-funds-export-csv');
    if (etfCsvBtn) {
        etfCsvBtn.addEventListener('click', function () {
            etfFundsTable.button('.dt-etf-funds-csv').trigger();
        });
    }
    const etfPdfBtn = document.getElementById('btn-etf-funds-export-pdf');
    if (etfPdfBtn) {
        etfPdfBtn.addEventListener('click', function () {
            etfFundsTable.button('.dt-etf-funds-pdf').trigger();
        });
    }
}

/**
 * Portfolio Manager (PTF) — Holdings list. Same DataTables pattern as recent transactions / ETF funds.
 */
function initPtfPortfolioHoldingsDataTable() {
    const el = document.getElementById('ptfHoldingsTable');
    if (!el) {
        return;
    }

    if (el.classList.contains('dataTable')) {
        return;
    }

    /** @typedef {{ holdingName: string; ticker: string; type: 'ETF' | 'PTF'; units: string; avgCost: string; currentPrice: string; marketValue: string; pnlHtml: string; pnlTone: 'up' | 'down'; weightPctLabel: string; weightBarPct: number; weightBarCssVar: string; }} HoldingRow */

    /** @type {HoldingRow[]} */
    const mockHoldings = [
        {
            holdingName: 'Vanguard S&P 500',
            ticker: 'VOO',
            type: 'ETF',
            units: '120',
            avgCost: '$488.20',
            currentPrice: '$512.40',
            marketValue: '$61,488',
            pnlHtml: '+$2,904',
            pnlTone: 'up',
            weightPctLabel: '32%',
            weightBarPct: 64,
            weightBarCssVar: 'var(--accent)',
        },
        {
            holdingName: 'Custom Growth PTF',
            ticker: 'CGF-A',
            type: 'PTF',
            units: '200',
            avgCost: '$82.50',
            currentPrice: '$88.75',
            marketValue: '$17,750',
            pnlHtml: '+$1,250',
            pnlTone: 'up',
            weightPctLabel: '18%',
            weightBarPct: 36,
            weightBarCssVar: 'var(--accent3)',
        },
        {
            holdingName: 'Tactical Bond PTF',
            ticker: 'TBP-2',
            type: 'PTF',
            units: '500',
            avgCost: '$105.10',
            currentPrice: '$102.30',
            marketValue: '$51,150',
            pnlHtml: '-$1,400',
            pnlTone: 'down',
            weightPctLabel: '26%',
            weightBarPct: 52,
            weightBarCssVar: 'var(--accent2)',
        },
    ];

    const ptfHoldingsTable = new DataTable('#ptfHoldingsTable', {
        data: mockHoldings,
        autoWidth: false,
        columns: [
            {
                data: null,
                title: 'Holding',
                type: 'string',
                width: '18%',
                render: function (_d, _t, row) {
                    return `<div class="fund-name">${escapeHtml(row.holdingName)}</div><div class="fund-ticker">${escapeHtml(row.ticker)}</div>`;
                },
            },
            {
                data: 'type',
                title: 'Type',
                type: 'string',
                width: '8%',
                render: function (data) {
                    return recentTransactionTypeHtml(data);
                },
            },
            {
                data: 'units',
                title: 'Units',
                type: 'string',
                width: '7%',
                render: function (data) {
                    return `<span class="td-mono">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'avgCost',
                title: 'Avg Cost',
                type: 'string',
                width: '10%',
                render: function (data) {
                    return `<span class="td-mono">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'currentPrice',
                title: 'Current Price',
                type: 'string',
                width: '11%',
                render: function (data) {
                    return `<span class="td-mono">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'marketValue',
                title: 'Market Value',
                type: 'string',
                width: '11%',
                render: function (data) {
                    return `<span class="td-mono" style="font-weight:500">${escapeHtml(data)}</span>`;
                },
            },
            {
                data: 'pnlHtml',
                title: 'P&L',
                type: 'string',
                width: '10%',
                createdCell: function (td, _cellData, rowData) {
                    td.classList.add(rowData.pnlTone === 'up' ? 'td-up' : 'td-down');
                },
                render: function (data) {
                    return escapeHtml(data);
                },
            },
            {
                data: null,
                title: 'Weight',
                type: 'string',
                width: '12%',
                orderable: false,
                render: function (_d, _t, row) {
                    return `<div style="display:flex;align-items:center;gap:8px"><span class="td-mono">${escapeHtml(row.weightPctLabel)}</span><div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:${row.weightBarPct}%;background:${row.weightBarCssVar}"></div></div></div>`;
                },
            },
            {
                data: null,
                title: 'Action',
                width: '13%',
                orderable: false,
                searchable: false,
                render: function () {
                    return '<div style="display:flex;gap:4px"><button type="button" class="btn btn-outline btn-sm">Buy</button><button type="button" class="btn btn-danger btn-sm">Sell</button></div>';
                },
            },
        ],
        dom: 'rt<"dt-custom-footer"ip>',
        ordering: true,
        paging: true,
        pageLength: 10,
        language: {
            info: 'Showing _START_ to _END_ of _TOTAL_ holdings',
            infoEmpty: 'No holdings found',
            paginate: {
                previous: '&lsaquo;',
                next: '&rsaquo;',
            },
        },
    });

    applyPtfPortfolioMobileColumns(ptfHoldingsTable);
    bindPtfPortfolioResize(ptfHoldingsTable);
}

document.addEventListener('DOMContentLoaded', function () {
    initSearchableFormSelects();
    initCustomerManagementDataTable();
    initRecentTransactionsDataTable();
    initEtfFundsDataTable();
    initPtfPortfolioHoldingsDataTable();
});
