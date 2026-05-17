@extends('layouts.layout')
@section('title', 'Customer Management')
@section('header_title', 'Customer Management')

@section('content')
    <div class="content page-animate customer-management-page">

        <header class="customer-mgmt-head page-head-row">
            <div>
                <x-ui.page-header title="Customer Management" subtitle="Manage your customer database" />
            </div>
            <x-ui.page-action-toolbar
                class="customer-mgmt-toolbar"
                :show-export-csv="true"
                :show-export-pdf="true"
                :show-bulk-upload="true"
                :show-add-customer="true"
            />
        </header>

        <section class="card customer-filters-card" aria-label="Customer filters">
            <div class="card-header customer-filters-toggle" role="button" tabindex="0" onclick="toggleFilters()" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleFilters();}">
                <span class="card-title"><i class="fa-solid fa-filter"></i> Filters</span>
                <i class="fa-solid fa-chevron-down" id="filter-icon" aria-hidden="true"></i>
            </div>
            <div class="card-body customer-filters-body" id="filter-body" hidden>
                <p class="customer-filters-placeholder">Advanced filter options will appear here.</p>
            </div>
        </section>

        <section class="card customer-table-card" aria-label="Customer list">
            <div class="card-header customer-table-header">
                <span class="card-title">All Customers (<span id="customer-count">0</span>)</span>

                <div class="card-header-actions customer-table-toolbar">
                    <div class="customer-columns-dropdown" id="customer-columns-dropdown">
                        <button
                            type="button"
                            class="btn btn-outline btn-sm"
                            id="customer-columns-toggle"
                            aria-expanded="false"
                            aria-haspopup="true"
                            aria-controls="customer-columns-menu"
                        >
                            Columns
                            <i class="fa-solid fa-chevron-down customer-columns-chevron" id="customer-columns-chevron" aria-hidden="true"></i>
                        </button>
                        <div
                            class="customer-columns-menu"
                            id="customer-columns-menu"
                            role="menu"
                            hidden
                        >
                            <div id="customer-columns-checkboxes"></div>
                        </div>
                    </div>

                    <div class="header-search customer-table-search">
                        <i class="fa-solid fa-magnifying-glass si"></i>
                        <input type="search" id="custom-searchBox" placeholder="Search customers…" aria-label="Search customers">
                    </div>
                </div>
            </div>

            <div class="table-wrap table-wrap--scroll-hint">
                <table id="customerTable" style="width: 100%;">
                    <tbody></tbody>
                </table>
            </div>
        </section>
    </div>

    <x-modals.add-customer-modal />
    <x-modals.edit-customer-modal />
    <x-modals.bulk-upload-customers-modal />

    @push('scripts')
    <script>
        function toggleFilters() {
            const body = document.getElementById('filter-body');
            const icon = document.getElementById('filter-icon');
            if (!body || !icon) {
                return;
            }
            const open = body.hasAttribute('hidden');
            if (open) {
                body.removeAttribute('hidden');
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                body.setAttribute('hidden', '');
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        }
    </script>
    @endpush
@endsection

