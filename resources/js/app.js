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

// ─────────────────────────────────────────────────────────────────────────────
// CUSTOMER MANAGEMENT PAGE — DataTables Initialization
//
// WHY this is here (not in the Blade file):
// Vite loads app.js as an ES Module which is always "deferred" by the browser.
// That means the browser FIRST finishes building the full HTML page, THEN runs
// this JS file. So by the time this code runs, the <table id="customerTable">
// is guaranteed to exist on screen. If we put this code in an inline <script>
// in the Blade file, it would run TOO EARLY and DataTable would not be defined.
// ─────────────────────────────────────────────────────────────────────────────

// WHY document.addEventListener: We wait for the DOM (the HTML elements) to be
// fully built before we try to grab any element by ID.
document.addEventListener('DOMContentLoaded', function () {

    // ── Only run this block if the customer table exists on the current page ──
    // WHY: app.js loads on EVERY page. Without this check, it would crash
    // on Dashboard, Profile, etc. because #customerTable won't exist there.
    const tableEl = document.getElementById('customerTable');
    if (!tableEl) return; // EXIT early if we're not on the Customer Management page

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 1 — MOCK DATA (Fake AJAX)
    //
    // WHY: We haven't built the backend API yet. Instead of connecting to a
    // real database, we define a JavaScript array that LOOKS exactly like the
    // JSON data a real server would return. Later, we replace this array with:
    //   fetch('/api/customers').then(res => res.json()).then(data => ...)
    // ─────────────────────────────────────────────────────────────────────────
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

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 2 — INITIALIZE DATATABLES
    //
    // WHY `new DataTable(...)`:  This is the core command. It takes our plain
    // HTML <table> and transforms it into a fully interactive table with
    // sorting arrows, search, pagination and export capabilities.
    //
    // WHY `#customerTable`: This is the CSS selector that tells DataTables
    // WHICH table to take over. It matches <table id="customerTable"> in Blade.
    // ─────────────────────────────────────────────────────────────────────────
    const table = new DataTable('#customerTable', {

        // WHY `data`: We give DataTables our mockCustomerData array directly.
        // When we switch to a real backend, we will replace this with `ajax: '/api/customers'`
        data: mockCustomerData,

        // WHY `columns`: DataTables needs to know which property from each data
        // object maps to which table column (left to right).
        columns: [
            // `data: 'id'` means: for this column, use the `id` property from our data object
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'address' },

            // WHY `render`: For the balance column, we want to COLOR the text.
            // The render function receives the raw data and returns custom HTML.
            // If the balance has a '-' (negative), show it in blue. Otherwise green.
            {
                data: 'balance',
                render: function (data) {
                    const color = data.includes('-') ? '#3b82f6' : '#22c55e';
                    return `<span style="color:${color}; font-weight:500">${data}</span>`;
                }
            },

            // WHY `render` for vehicles: We wrap the text in a blue badge pill
            { data: 'vehicles', render: function (data) { return `<span class="badge badge-blue">${data}</span>`; } },

            { data: 'last_transaction' },

            // WHY `data: null`: The Actions column doesn't map to any data property.
            // `render` returns two icon buttons: Edit and Delete.
            // `orderable: false` disables sorting on this column (no up/down arrow).
             // this btn-icon-pill is a custom button class that we defined in app.css
            {
                data: null, 
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

        // WHY `dom` with custom wrapper `<"dt-custom-footer"ip>`:
        // DataTables `dom` string supports custom HTML wrappers using this syntax:
        //   <"className"...> = Wrap elements in a <div class="className">
        //
        // So 'rt<"dt-custom-footer"ip>' means:
        //   r = Processing spinner
        //   t = The actual table
        //   <"dt-custom-footer"ip> = Wrap BOTH info(i) and pagination(p) together
        //                             inside a <div class="dt-custom-footer">
        //
        // Then in app.css we style .dt-custom-footer with flexbox:
        //   info text floats LEFT, pagination floats RIGHT — exactly like your screenshot goal.
        dom: 'rt<"dt-custom-footer"ip>',

        // WHY `buttons`: Even though the buttons are hidden by `dom`, we still
        // register them here so we can TRIGGER them programmatically when the user
        // clicks our custom "Export CSV" and "Export PDF" buttons in the header.
        buttons: [
            {
                extend: 'csvHtml5',      // Built-in CSV export driver
                text: 'Export CSV',
                className: 'dt-btn-csv', // custom class so we can find this button
            },
            {
                extend: 'pdfHtml5',      // Built-in PDF export driver
                text: 'Export PDF',
                className: 'dt-btn-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
            }
        ],

        // WHY `ordering: true`: This ENABLES the up/down sort arrows on column headers.
        // When the user clicks a column header, DataTables sorts all rows automatically.
        ordering: true,

        // WHY `paging`: Shows pagination at the bottom (Page 1, 2, 3...)
        paging: true,
        pageLength: 10, // Show 10 rows per page

        // WHY `language`: Override the default English text with our own labels
        language: {
            info:          'Showing _START_ to _END_ of _TOTAL_ customers',
            infoEmpty:     'No customers found',
            paginate: {
                previous: '&lsaquo;',
                next:     '&rsaquo;',
            }
        },

        // WHY `initComplete`: This callback fires AFTER DataTables finishes drawing
        // the table for the first time. We use it to update the "All Customers (0)"
        // count badge with the real number of records.
        initComplete: function () {
            const count = this.api().data().length;
            const countEl = document.getElementById('customer-count');
            if (countEl) countEl.innerText = count;
        }
    });

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 3 — CONNECT OUR CUSTOM SEARCH BAR
    //
    // WHY: We hid the ugly default search bar using `dom: 'Brtip'`.
    // Our own search bar in the header has id="custom-searchBox".
    // We "listen" for every keypress. When the user types, we grab their
    // text and pass it to table.search() which filters the rows live.
    // ─────────────────────────────────────────────────────────────────────────
    const searchBox = document.getElementById('custom-searchBox');
    if (searchBox) {
        searchBox.addEventListener('keyup', function () {
            table.search(this.value).draw();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 4 — CONNECT CUSTOM EXPORT BUTTONS
    //
    // WHY: DataTables already created CSV and PDF buttons internally (from the
    // `buttons` config above). But they are visually hidden. When the user clicks
    // OUR beautiful "Export CSV" button in the page header, we TRIGGER the
    // hidden DataTables button programmatically. It's like a remote control!
    // ─────────────────────────────────────────────────────────────────────────
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

}); // end DOMContentLoaded
