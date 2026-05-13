{{--
  Reports & Export — "Generate Report" card body (same form primitives as etf-funds-filter-form / etf-parameters-form).
  Page: resources/views/pages/reports.blade.php uses <x-report-generate-form /> inside the card.
  Primitives: x-form-label, x-form-select, x-form-input, x-form-textarea, x-form-button.
--}}

<div class="form-group">
    <x-form-label for="report-type">Report Type</x-form-label>
    <x-form-select id="report-type" name="report_type">
        <option value="ETF Performance Summary" selected>ETF Performance Summary</option>
        <option value="PTF Portfolio Statement">PTF Portfolio Statement</option>
        <option value="Tax Report (Annual)">Tax Report (Annual)</option>
        <option value="Compliance Audit">Compliance Audit</option>
    </x-form-select>
</div>

<div class="form-row">
    <div class="form-group">
        <x-form-label for="report-from-date">From Date</x-form-label>
        <x-form-input id="report-from-date" name="from_date" type="date" value="2026-01-01" />
    </div>
    <div class="form-group">
        <x-form-label for="report-to-date">To Date</x-form-label>
        <x-form-input id="report-to-date" name="to_date" type="date" value="2026-05-03" />
    </div>
</div>

<div class="form-group">
    <x-form-label for="report-export-format">Export Format</x-form-label>
    <x-form-select id="report-export-format" name="export_format">
        <option value="PDF" selected>PDF</option>
        <option value="Excel (.xlsx)">Excel (.xlsx)</option>
        <option value="CSV">CSV</option>
        <option value="JSON">JSON</option>
    </x-form-select>
</div>

<div class="form-group">
    <x-form-label for="report-notes">Notes / Comments</x-form-label>
    <x-form-textarea id="report-notes" name="notes" placeholder="Optional report notes…" rows="4" />
</div>

<x-form-button
    type="button"
    icon="fa-file-export"
    full-width
    onclick="showToast('success','Report Generated','Your report is ready for download.')"
>
    Generate &amp; Download
</x-form-button>
