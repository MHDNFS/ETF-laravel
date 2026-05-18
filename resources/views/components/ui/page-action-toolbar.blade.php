{{--
  Reusable header action buttons (export, bulk upload, primary CTA).
  Toggle each action with show* props. IDs default to employees page hooks in resources/js/app.js;
  override per page when you wire different script behavior.

  Optional default slot: extra buttons rendered after the configured actions.
--}}
@props([
    'showExportCsv' => false,
    'showExportPdf' => false,
    'showBulkUpload' => false,
    'showAddEmployee' => false,
    'exportCsvId' => 'btn-export-csv',
    'exportPdfId' => 'btn-export-pdf',
    'bulkUploadId' => 'btn-bulk-upload-employees',
    'addEmployeeId' => 'btn-add-employee',
    'exportCsvLabel' => 'Export CSV',
    'exportPdfLabel' => 'Export PDF',
    'bulkUploadLabel' => 'Bulk upload',
    'addEmployeeLabel' => 'Add Employee',
])

<div {{ $attributes->merge(['style' => 'display: flex; gap: 10px; flex-wrap: wrap; align-items: center;']) }}>
    @if ($showExportCsv)
        <button type="button" class="btn btn-outline btn-sm" id="{{ $exportCsvId }}">
            <i class="fa-regular fa-circle-check"></i> {{ $exportCsvLabel }}
        </button>
    @endif

    @if ($showExportPdf)
        <button type="button" class="btn btn-outline btn-sm" id="{{ $exportPdfId }}" style="color: #22c55e; border-color: #22c55e;">
            <i class="fa-solid fa-file-pdf"></i> {{ $exportPdfLabel }}
        </button>
    @endif

    @if ($showBulkUpload)
        <button type="button" class="btn btn-outline btn-sm" id="{{ $bulkUploadId }}" style="color: #eab308; border-color: rgba(234, 179, 8, 0.45);">
            <i class="fa-solid fa-cloud-arrow-up"></i> {{ $bulkUploadLabel }}
        </button>
    @endif

    @if ($showAddEmployee)
        <button type="button" class="btn btn-blue btn-sm" id="{{ $addEmployeeId }}">
            <i class="fa-solid fa-plus"></i> {{ $addEmployeeLabel }}
        </button>
    @endif

    {{ $slot }}
</div>
