{{--
  Bulk upload — files or an entire folder (webkitdirectory). Server handling is a separate step (route + controller).
--}}
<div class="modal-overlay" id="bulk-upload-customers-modal">
    <div class="modal" style="max-width: 560px">
        <div class="modal-header">
            <span class="modal-title" id="bulk-upload-modal-title">
                <i class="fa-solid fa-cloud-arrow-up" style="color:var(--accent);margin-right:8px"></i>Bulk upload
            </span>
            <button type="button" class="modal-close" onclick="closeModal('bulk-upload-customers-modal')" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text3); font-size: 13px; margin: 0 0 16px;">
                Select multiple files (Word, Excel, PowerPoint, PDF, CSV, etc.) or choose a folder to upload everything inside it.
             <code style="font-size:12px"></code>
            </p>

            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;">
                <button type="button" class="btn btn-outline btn-sm" id="bulk-upload-pick-files">
                    <i class="fa-regular fa-file-lines"></i> Choose files
                </button>
                <button type="button" class="btn btn-outline btn-sm" id="bulk-upload-pick-folder" style="color: #eab308; border-color: rgba(234, 179, 8, 0.45);">
                    <i class="fa-regular fa-folder-open"></i> Choose folder
                </button>
            </div>

            <input type="file" id="bulk-upload-file-input" multiple style="display: none;">
            <input type="file" id="bulk-upload-folder-input" multiple style="display: none;" webkitdirectory>

            <div class="card" style="margin: 0; box-shadow: none;">
                <div class="card-header" style="padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                    <span class="card-title" style="font-size: 13px;">Selected (<span id="bulk-upload-count">0</span>)</span>
                    <button type="button" class="btn btn-outline btn-sm" id="bulk-upload-clear" style="display: none;">Clear list</button>
                </div>
                <div class="card-body" style="padding: 10px 14px; max-height: 200px; overflow-y: auto;">
                    <ul id="bulk-upload-file-list" style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text2);"></ul>
                    <p id="bulk-upload-empty-hint" style="margin: 0; font-size: 13px; color: var(--text3);">No files selected yet.</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('bulk-upload-customers-modal')">Cancel</button>
            <button type="button" class="btn btn-blue" id="bulk-upload-submit" disabled>Upload</button>
        </div>
    </div>
</div>
