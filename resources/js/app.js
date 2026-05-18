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
window.pdfMake = pdfMake;

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
// EMPLOYEES PAGE — DataTables (runs only if #employeeTable exists)
// DASHBOARD — Recent Transactions — DataTables (runs only if #recentTransactionsTable exists)
//
// WHY this is here (not in the Blade file):
// Vite loads app.js as an ES Module which is always "deferred" by the browser.
// That means the browser FIRST finishes building the full HTML page, THEN runs
// this JS file. So by the time this code runs, the <table id="..."> is present.
// ─────────────────────────────────────────────────────────────────────────────

/** Live reference for Employee DataTable (add/edit modals on employees page). */
let employeesDataTable = null;

/** Cursor pagination state for the employees table (infinite scroll). */
const EMPLOYEES_PAGE_SIZE = 25;
let employeesNextCursor = null;
let employeesHasMore = false;
let employeesLoadingMore = false;
let employeesSearchTerm = '';
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

const EMPLOYEES_MGMT_MOBILE_BP = 700;
const EMPLOYEES_MGMT_MOBILE_BP_SM = 520;

/** Hide non-essential Employee columns on narrow viewports. */
function applyEmployeesMobileColumns(table) {
    if (!table || !document.getElementById('employeeTable')) {
        return;
    }

    const compact = window.innerWidth < EMPLOYEES_MGMT_MOBILE_BP;
    const extraCompact = window.innerWidth < EMPLOYEES_MGMT_MOBILE_BP_SM;

    // Company, Branch, Designation, Bank Account, Status — hide on narrow screens
    table.column(0).visible(!compact);
    table.column(4).visible(!compact);
    table.column(5).visible(!compact);
    table.column(6).visible(!compact);
    table.column(7).visible(!compact);
    // NIC, EPF No — hide on very narrow screens
    table.column(2).visible(!extraCompact);
    table.column(3).visible(!extraCompact);
    table.columns.adjust().draw(false);
}

let employeesMgmtResizeTimer = null;

function bindEmployeesResize(table) {
    window.addEventListener('resize', function () {
        clearTimeout(employeesMgmtResizeTimer);
        employeesMgmtResizeTimer = setTimeout(function () {
            applyEmployeesMobileColumns(table);
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

/**
 * Fetch one page of employees (cursor pagination).
 * @returns {{ data: object[], nextCursor: string|null, hasMore: boolean }}
 */
async function fetchEmployeesPage(cursor, params) {
    const Auth = window.MaraWebAuth;
    if (!Auth || !Auth.getToken()) {
        return { data: [], nextCursor: null, hasMore: false };
    }

    const query = new URLSearchParams();
    const filters = params || {};
    const perPage = filters.perPage ?? EMPLOYEES_PAGE_SIZE;
    query.set('per_page', String(perPage));
    if (filters.search) {
        query.set('search', filters.search);
    }
    if (filters.company) {
        query.set('company', filters.company);
    }
    if (filters.branch) {
        query.set('branch', filters.branch);
    }
    if (filters.status) {
        query.set('status', filters.status);
    }
    if (cursor) {
        query.set('cursor', cursor);
    }

    try {
        const result = await Auth.apiFetch(`/api/employees?${query.toString()}`);
        if (!result.response.ok) {
            console.error('Employees API error', result.response.status, result.data);
            return { data: [], nextCursor: null, hasMore: false };
        }

        const payload = result.data || {};
        const data = Array.isArray(payload.data) ? payload.data : [];
        const meta = payload.meta || {};

        return {
            data: data,
            nextCursor: meta.next_cursor ?? null,
            hasMore: Boolean(meta.has_more),
        };
    } catch (e) {
        return { data: [], nextCursor: null, hasMore: false };
    }
}

function updateEmployeesScrollStatus(loadedCount) {
    const statusEl = document.getElementById('employees-scroll-status');
    if (!statusEl) {
        return;
    }

    if (loadedCount === 0) {
        statusEl.textContent = '';
        statusEl.hidden = true;
        return;
    }

    statusEl.hidden = false;

    if (employeesLoadingMore) {
        statusEl.textContent = 'Loading more employees…';
        return;
    }

    if (employeesHasMore) {
        statusEl.textContent = `${loadedCount} loaded — scroll down for more`;
        return;
    }

    statusEl.textContent = `${loadedCount} employee${loadedCount === 1 ? '' : 's'} loaded`;
}

const EMPLOYEE_EXPORT_COLUMNS = [
    { key: 'company', label: 'Company' },
    { key: 'name', label: 'Employee' },
    { key: 'nic', label: 'NIC' },
    { key: 'epf_no', label: 'EPF No' },
    { key: 'branch', label: 'Branch' },
    { key: 'designation', label: 'Designation' },
    { key: 'bank_account', label: 'Bank Account' },
    { key: 'status', label: 'Status' },
];

function employeeExportPlainValue(row, key) {
    const raw = row[key];
    if (raw === null || raw === undefined) {
        return '';
    }

    const text = String(raw).trim();
    if (text === '' || text === '—' || text === 'N/A') {
        return '';
    }

    if (key === 'status') {
        return text.toLowerCase() === 'active' ? 'active' : 'deactive';
    }

    return text;
}

function escapeCsvField(value) {
    const text = String(value ?? '');
    if (/[",\n\r]/.test(text)) {
        return `"${text.replace(/"/g, '""')}"`;
    }

    return text;
}

function employeesExportFilename(extension) {
    const stamp = new Date().toISOString().slice(0, 10);

    return `employees-${stamp}.${extension}`;
}

/**
 * Walk every cursor page so export includes all rows (not only what is loaded in the table).
 */
async function fetchAllEmployeesForExport(filters) {
    const all = [];
    let cursor = null;
    let hasMore = true;

    while (hasMore) {
        const page = await fetchEmployeesPage(cursor, {
            ...(filters || {}),
            perPage: 100,
        });

        if (page.data.length) {
            all.push(...page.data);
        }

        cursor = page.nextCursor;
        hasMore = page.hasMore;

        if (!page.data.length && !hasMore) {
            break;
        }
    }

    return all;
}

function downloadEmployeesCsv(rows) {
    const headerLine = EMPLOYEE_EXPORT_COLUMNS.map((col) => escapeCsvField(col.label)).join(',');
    const dataLines = rows.map((row) =>
        EMPLOYEE_EXPORT_COLUMNS.map((col) =>
            escapeCsvField(employeeExportPlainValue(row, col.key))
        ).join(',')
    );

    const csv = `\uFEFF${[headerLine, ...dataLines].join('\r\n')}`;
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = employeesExportFilename('csv');
    link.click();
    URL.revokeObjectURL(url);
}

function downloadEmployeesPdf(rows) {
    const tableHeader = EMPLOYEE_EXPORT_COLUMNS.map((col) => ({
        text: col.label,
        style: 'tableHeader',
        bold: true,
    }));

    const tableBody = rows.map((row) =>
        EMPLOYEE_EXPORT_COLUMNS.map((col) => employeeExportPlainValue(row, col.key))
    );

    const docDefinition = {
        pageOrientation: 'landscape',
        pageSize: 'A4',
        content: [
            { text: 'Employees', style: 'title' },
            {
                text: `Exported on ${new Date().toLocaleString()} — ${rows.length} record(s)`,
                style: 'subtitle',
                margin: [0, 0, 0, 10],
            },
            {
                table: {
                    headerRows: 1,
                    widths: ['auto', '*', 'auto', 'auto', 'auto', '*', '*', 'auto'],
                    body: [tableHeader, ...tableBody],
                },
                layout: 'lightHorizontalLines',
            },
        ],
        styles: {
            title: { fontSize: 14, bold: true },
            subtitle: { fontSize: 9, color: '#555555' },
            tableHeader: { fontSize: 8, fillColor: '#1e2b6e', color: '#ffffff' },
        },
        defaultStyle: { fontSize: 8 },
    };

    pdfMake.createPdf(docDefinition).download(employeesExportFilename('pdf'));
}

async function exportEmployees(format) {
    const csvBtn = document.getElementById('btn-export-csv');
    const pdfBtn = document.getElementById('btn-export-pdf');
    const buttons = [csvBtn, pdfBtn].filter(Boolean);

    buttons.forEach((btn) => {
        btn.disabled = true;
    });

    try {
        if (typeof showToast === 'function') {
            showToast('info', 'Export', 'Preparing file…');
        }

        const rows = await fetchAllEmployeesForExport({ search: employeesSearchTerm });

        if (!rows.length) {
            if (typeof showToast === 'function') {
                showToast('info', 'Export', 'No employees to export.');
            }
            return;
        }

        if (format === 'csv') {
            downloadEmployeesCsv(rows);
        } else {
            downloadEmployeesPdf(rows);
        }

        if (typeof showToast === 'function') {
            showToast('success', 'Export', `Downloaded ${rows.length} employee(s) as ${format.toUpperCase()}.`);
        }
    } catch (error) {
        console.error('Employee export failed', error);
        if (typeof showToast === 'function') {
            showToast('error', 'Export', error.message || 'Could not export employees.');
        }
    } finally {
        buttons.forEach((btn) => {
            btn.disabled = false;
        });
    }
}

async function initEmployeesDataTable() {
    const tableEl = document.getElementById('employeeTable');
    if (!tableEl) {
        return;
    }

    if (tableEl.classList.contains('dataTable')) {
        return;
    }

    let editingEmployeeId = null;

    function setEmployeeField(id, value) {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }
        el.value = value != null && value !== '' ? String(value) : '';
    }

    function apiValueToInput(value) {
        const t = String(value ?? '').trim();

        return t === '—' || t === 'N/A' ? '' : t;
    }

    function readEmployeeForm(prefix) {
        const el = (suffix) => document.getElementById(`${prefix}-${suffix}`);

        return {
            company: getSelectElementValue(el('company')) || 'ABS',
            name: el('name')?.value.trim() ?? '',
            nic: el('nic')?.value.trim() ?? '',
            epf_no: el('epf-no')?.value.trim() ?? '',
            branch: getSelectElementValue(el('branch')) || 'ABS',
            designation: el('designation')?.value.trim() ?? '',
            bank_account: el('bank-account')?.value.trim() ?? '',
            status: getSelectElementValue(el('status')) || 'active',
        };
    }

    function fillEmployeeForm(prefix, row) {
        const el = (suffix) => document.getElementById(`${prefix}-${suffix}`);

        setEmployeeField(`${prefix}-name`, row.name);
        setEmployeeField(`${prefix}-nic`, apiValueToInput(row.nic));
        setEmployeeField(`${prefix}-epf-no`, apiValueToInput(row.epf_no));
        setEmployeeField(`${prefix}-designation`, apiValueToInput(row.designation));
        setEmployeeField(`${prefix}-bank-account`, apiValueToInput(row.bank_account));

        if (el('company')) {
            setSelectElementValue(el('company'), row.company || 'ABS');
        }
        if (el('branch')) {
            setSelectElementValue(el('branch'), row.branch || 'ABS');
        }
        if (el('status')) {
            setSelectElementValue(el('status'), row.status || 'active');
        }
    }

    function resetAddEmployeeForm() {
        fillEmployeeForm('add-employee', {
            company: 'ABS',
            name: '',
            nic: '',
            epf_no: '',
            branch: 'ABS',
            designation: '',
            bank_account: '',
            status: 'active',
        });
        const errorEl = document.getElementById('add-employee-form-error');
        if (errorEl) {
            errorEl.hidden = true;
            errorEl.textContent = '';
        }
    }

    function fillEditEmployeeForm(row) {
        setEmployeeField('edit-employee-id', row.id);
        fillEmployeeForm('edit-employee', row);
        const errorEl = document.getElementById('edit-employee-form-error');
        if (errorEl) {
            errorEl.hidden = true;
            errorEl.textContent = '';
        }
    }

    function showEmployeeFormError(errorElId, message) {
        const errorEl = document.getElementById(errorElId);
        if (errorEl) {
            errorEl.textContent = message || 'Unable to save employee.';
            errorEl.hidden = false;
        }
    }

    async function persistEmployeeApi(method, url, payload) {
        const Auth = window.MaraWebAuth;
        if (!Auth || !Auth.getToken()) {
            throw new Error('You must be signed in to save employees.');
        }

        const result = await Auth.apiFetch(url, {
            method: method,
            body: payload,
        });

        if (!result.response.ok) {
            let message = result.data?.message || 'Unable to save employee.';
            if (result.data?.errors) {
                const firstKey = Object.keys(result.data.errors)[0];
                if (firstKey && result.data.errors[firstKey][0]) {
                    message = result.data.errors[firstKey][0];
                }
            }
            throw new Error(message);
        }

        return result.data?.data ?? result.data;
    }

    async function deleteEmployeeApi(id) {
        const Auth = window.MaraWebAuth;
        if (!Auth || !Auth.getToken()) {
            throw new Error('You must be signed in to delete employees.');
        }

        const result = await Auth.apiFetch(`/api/employees/${id}`, {
            method: 'DELETE',
        });

        if (!result.response.ok) {
            throw new Error(result.data?.message || 'Unable to delete employee.');
        }
    }

    async function loadEmployeesFirstPage(tableApi) {
        employeesNextCursor = null;
        employeesHasMore = false;

        const page = await fetchEmployeesPage(null, { search: employeesSearchTerm });
        employeesNextCursor = page.nextCursor;
        employeesHasMore = page.hasMore;

        tableApi.clear();
        if (page.data.length) {
            tableApi.rows.add(page.data);
        }
        tableApi.draw(false);
        refreshEmployeeCountBadge(tableApi);
        updateEmployeesScrollStatus(tableApi.rows().count());
    }

    async function loadMoreEmployees(tableApi) {
        if (!employeesHasMore || employeesLoadingMore || !employeesNextCursor) {
            return;
        }

        employeesLoadingMore = true;
        updateEmployeesScrollStatus(tableApi.rows().count());

        const page = await fetchEmployeesPage(employeesNextCursor, { search: employeesSearchTerm });
        employeesNextCursor = page.nextCursor;
        employeesHasMore = page.hasMore;

        if (page.data.length) {
            tableApi.rows.add(page.data);
            tableApi.draw(false);
        }

        employeesLoadingMore = false;
        refreshEmployeeCountBadge(tableApi);
        updateEmployeesScrollStatus(tableApi.rows().count());
    }

    async function reloadEmployeesTable(tableApi) {
        await loadEmployeesFirstPage(tableApi);
    }

    function bindEmployeesInfiniteScroll(tableApi, tableElement) {
        const scrollWrap = tableElement.closest('.employees-table-scroll');
        if (!scrollWrap) {
            return;
        }

        scrollWrap.addEventListener('scroll', () => {
            if (employeesLoadingMore || !employeesHasMore) {
                return;
            }

            const nearBottom =
                scrollWrap.scrollTop + scrollWrap.clientHeight >= scrollWrap.scrollHeight - 80;

            if (nearBottom) {
                loadMoreEmployees(tableApi);
            }
        });
    }

    function findEmployeeRowApiById(tableApi, id) {
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

    function nextEmployeeId(tableApi) {
        let max = 0;
        tableApi.rows().every(function () {
            const n = parseInt(String(this.data().id), 10);
            if (!Number.isNaN(n) && n > max) {
                max = n;
            }
        });
        return max + 1;
    }

    function refreshEmployeeCountBadge(tableApi) {
        const countEl = document.getElementById('employee-count');
        if (countEl) {
            countEl.textContent = String(tableApi.rows().count());
        }
    }

    /**
     * Build the "Columns" dropdown: toggle DataTables visibility for every column except the last (Actions).
     */
    function setupEmployeeTableColumnMenu(tableApi, tableElement) {
        const wrap = document.getElementById('employee-columns-dropdown');
        const toggle = document.getElementById('employee-columns-toggle');
        const menu = document.getElementById('employee-columns-menu');
        const list = document.getElementById('employee-columns-checkboxes');
        const chevron = document.getElementById('employee-columns-chevron');

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
            row.className = 'employees-columns-menu__row';
            row.setAttribute('role', 'menuitemcheckbox');

            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.id = `Employee-col-vis-${i}`;
            cb.checked = tableApi.column(i).visible();

            const label = document.createElement('label');
            label.htmlFor = cb.id;
            label.textContent = title;

            cb.addEventListener('change', () => {
                if (!cb.checked) {
                    let otherChecked = 0;
                    for (let j = 0; j <= lastDataColIndex; j++) {
                        const el = list.querySelector(`#Employee-col-vis-${j}`);
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

    function employeeTableInitials(name) {
        const parts = String(name || '').trim().split(/\s+/).filter(Boolean);
        if (parts.length >= 2) {
            return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
        }
        if (parts.length === 1) {
            return parts[0].slice(0, 2).toUpperCase();
        }

        return '—';
    }

    function renderEmployeeEmpty(value) {
        const t = String(value ?? '').trim();
        if (t === '' || t === '—' || t === 'N/A') {
            return '<span class="dt-cell-muted">—</span>';
        }

        return null;
    }

    const table = new DataTable('#employeeTable', {
        data: [],
        deferRender: true,
        autoWidth: false,
        columns: [
            {
                data: 'company',
                title: 'Company',
                width: '8%',
                render: function (data) {
                    return `<span class="badge badge-green">${data}</span>`;
                },
            },
            {
                data: 'name',
                title: 'Employee',
                width: '16%',
                render: function (data) {
                    const initials = employeeTableInitials(data);

                    return `<span class="dt-employee-cell"><span class="dt-employee-avatar" aria-hidden="true">${initials}</span><span class="dt-employee-name">${data}</span></span>`;
                },
            },
            {
                data: 'nic',
                title: 'NIC',
                type: 'string',
                width: '12%',
                render: function (data) {
                    const empty = renderEmployeeEmpty(data);

                    return empty || `<span class="badge badge-muted">${data}</span>`;
                },
            },
            {
                data: 'epf_no',
                title: 'EPF No',
                type: 'string',
                width: '8%',
                render: function (data) {
                    const empty = renderEmployeeEmpty(data);

                    return empty || `<span class="dt-epf-no">${data}</span>`;
                },
            },
            {
                data: 'branch',
                title: 'Branch',
                width: '8%',
                render: function (data) {
                    return `<span class="badge badge-blue">${data}</span>`;
                },
            },
            {
                data: 'designation',
                title: 'Designation',
                width: '12%',
                render: function (data) {
                    return renderEmployeeEmpty(data) || data;
                },
            },
            {
                data: 'bank_account',
                title: 'Bank Account',
                width: '12%',
                render: function (data) {
                    return renderEmployeeEmpty(data) || data;
                },
            },
            {
                data: 'status',
                title: 'Status',
                width: '8%',
                render: function (data) {
                    const raw = String(data || 'active').toLowerCase();
                    const isActive = raw === 'active';
                    const label = isActive ? 'active' : 'deactive';
                    const badgeClass = isActive ? 'badge-green' : 'badge-muted';

                    return `<span class="badge ${badgeClass}">${label}</span>`;
                },
            },
            {
                data: null,
                title: 'Actions',
                width: '10%',
                orderable: false,
                render: function () {
                    return `
                        <span class="dt-actions-cell">
                        <button type="button" class="btn btn-outline btn-sm btn-icon-pill js-edit-employee" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn btn-outline btn-sm btn-icon-pill btn-outline--danger js-delete-employee" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </span>
                    `;
                }
            }
        ],
        dom: 'rt<"dt-custom-footer"i>',
        ordering: true,
        paging: false,
        language: {
            info:          '_TOTAL_ employees loaded',
            infoEmpty:     'No employees found',
        },
        initComplete: function () {
            const api = this.api();
            refreshEmployeeCountBadge(api);
            setupEmployeeTableColumnMenu(api, tableEl);
        }
    });

    employeesDataTable = table;
    applyEmployeesMobileColumns(table);
    bindEmployeesResize(table);
    bindEmployeesInfiniteScroll(table, tableEl);

    try {
        await loadEmployeesFirstPage(table);
    } catch (loadError) {
        console.error('Failed to load employees', loadError);
        if (typeof showToast === 'function') {
            showToast('error', 'Employees', 'Could not load employee list. Try signing in again.');
        }
    }

    const addEmployeeBtn = document.getElementById('btn-add-employee');
    if (addEmployeeBtn) {
        addEmployeeBtn.addEventListener('click', () => {
            resetAddEmployeeForm();
            showModal('add-employee-modal');
            initSearchableFormSelects(document.getElementById('add-employee-modal'));
        });
    }

    const addEmployeeSave = document.getElementById('add-employee-save');
    if (addEmployeeSave) {
        addEmployeeSave.addEventListener('click', async () => {
            const payload = readEmployeeForm('add-employee');
            if (!payload.name) {
                showEmployeeFormError('add-employee-form-error', 'Employee name is required.');
                return;
            }

            addEmployeeSave.disabled = true;

            try {
                await persistEmployeeApi('POST', '/api/employees', payload);
                await reloadEmployeesTable(table);
                closeModal('add-employee-modal');
                showToast('success', 'Employee added', 'Employee saved to the database.');
            } catch (error) {
                showEmployeeFormError('add-employee-form-error', error.message);
            } finally {
                addEmployeeSave.disabled = false;
            }
        });
    }

    const editEmployeeSave = document.getElementById('edit-employee-save');
    if (editEmployeeSave) {
        editEmployeeSave.addEventListener('click', async () => {
            if (editingEmployeeId === null) {
                return;
            }

            const payload = readEmployeeForm('edit-employee');
            if (!payload.name) {
                showEmployeeFormError('edit-employee-form-error', 'Employee name is required.');
                return;
            }

            editEmployeeSave.disabled = true;

            try {
                await persistEmployeeApi('PUT', `/api/employees/${editingEmployeeId}`, payload);
                await reloadEmployeesTable(table);
                editingEmployeeId = null;
                closeModal('edit-employee-modal');
                showToast('success', 'Employee updated', 'Changes saved to the database.');
            } catch (error) {
                showEmployeeFormError('edit-employee-form-error', error.message);
            } finally {
                editEmployeeSave.disabled = false;
            }
        });
    }

    tableEl.addEventListener('click', async (e) => {
        const deleteBtn = e.target.closest('.js-delete-employee');
        if (deleteBtn && tableEl.contains(deleteBtn)) {
            const tr = deleteBtn.closest('tr');
            const rowApi = table.row(tr);
            if (!rowApi.length) {
                return;
            }

            const rowData = rowApi.data();
            const employeeName = rowData.name || 'this employee';

            if (!window.confirm(`Delete ${employeeName}? This cannot be undone.`)) {
                return;
            }

            try {
                await deleteEmployeeApi(rowData.id);

                if (String(editingEmployeeId) === String(rowData.id)) {
                    editingEmployeeId = null;
                    closeModal('edit-employee-modal');
                }

                await reloadEmployeesTable(table);
                showToast('success', 'Employee deleted', `${employeeName} was removed from the database.`);
            } catch (error) {
                showToast('error', 'Delete failed', error.message || 'Unable to delete employee.');
            }

            return;
        }

        const editBtn = e.target.closest('.js-edit-employee');
        if (!editBtn || !tableEl.contains(editBtn)) {
            return;
        }

        const tr = editBtn.closest('tr');
        const rowApi = table.row(tr);
        if (!rowApi.length) {
            return;
        }

        const rowData = rowApi.data();
        editingEmployeeId = rowData.id;
        fillEditEmployeeForm(rowData);
        showModal('edit-employee-modal');
        initSearchableFormSelects(document.getElementById('edit-employee-modal'));
    });

    const searchBox = document.getElementById('custom-searchBox');
    let employeesSearchDebounce = null;
    if (searchBox) {
        searchBox.addEventListener('input', function () {
            clearTimeout(employeesSearchDebounce);
            employeesSearchDebounce = setTimeout(async () => {
                employeesSearchTerm = searchBox.value.trim();
                await loadEmployeesFirstPage(table);
            }, 350);
        });
    }

    const csvBtn = document.getElementById('btn-export-csv');
    if (csvBtn) {
        csvBtn.addEventListener('click', () => {
            exportEmployees('csv');
        });
    }

    const pdfBtn = document.getElementById('btn-export-pdf');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', () => {
            exportEmployees('pdf');
        });
    }

    const bulkUploadBtn = document.getElementById('btn-bulk-upload-employees');
    const bulkModalId = 'bulk-upload-employees-modal';
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
    initEmployeesDataTable();
    initRecentTransactionsDataTable();
    initEtfFundsDataTable();
    initPtfPortfolioHoldingsDataTable();
});
