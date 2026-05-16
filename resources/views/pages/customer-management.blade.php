@extends('layouts.layout')
@section('title', 'Customer Management')
@section('header_title', 'Customer Management')

@section('content')
    <div class="content page-animate">
        {{-- TABLE: Customers (#customerTable) — full toolbar: export, bulk upload, add customer (app.js initCustomerManagementDataTable). --}}
        <!-- Header Section -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <x-ui.page-header title="Customer Management" subtitle="Manage your customer database" />
            </div>
            <x-ui.page-action-toolbar
                :show-export-csv="true"
                :show-export-pdf="true"
                :show-bulk-upload="true"
                :show-add-customer="true"
            />
        </div>

        <!-- Filters Section -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header" style="cursor: pointer;" onclick="toggleFilters()">
                <span class="card-title"><i class="fa-solid fa-filter"></i> Filters</span>
                <i class="fa-solid fa-chevron-down" id="filter-icon"></i>
            </div>
            <div class="card-body" id="filter-body" style="display: none;">
                <!-- We will put dropdowns here later -->
                <p style="color: var(--text3); font-size: 13px;">Advanced filter options will appear here.</p>
            </div>
        </div>

        <!-- Datatable Section — #customerTable -->
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <span class="card-title">All Customers (<span id="customer-count">0</span>)</span>

                <div style="display: flex; gap: 10px; align-items: center;">
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
                            <i class="fa-solid fa-chevron-down" id="customer-columns-chevron" style="font-size: 10px; margin-left: 5px;"></i>
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

                    <!-- Custom Search Bar -->
                    <div class="header-search" style="margin: 0;">
                        <i class="fa-solid fa-magnifying-glass si"></i>
                        <input type="text" id="custom-searchBox" placeholder="Search..." style="width: 200px;">
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                {{-- DataTables builds <thead> from columns[].title — avoid a static <thead> row (prevents doubled / misaligned sort UI) --}}
                <table id="customerTable" style="width: 100%;">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modals.add-customer-modal />
    <x-modals.edit-customer-modal />
    <x-modals.bulk-upload-customers-modal />

    <script>
        {{-- 
            WHY is this script here and not in app.js?
            This toggleFilters() function is called via onclick="toggleFilters()" 
            directly in the HTML above. onclick="" attributes need the function 
            to be in the GLOBAL scope. app.js runs as an ES module (private scope),
            so functions defined there are NOT accessible via onclick="".
            Simple UI helper functions like this one BELONG in the Blade file.
        --}}
        function toggleFilters() {
            const body = document.getElementById('filter-body');
            const icon = document.getElementById('filter-icon');

            // If hidden → show it and flip the icon to point UP
            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                // If visible → hide it and flip the icon back DOWN
                body.style.display = 'none';
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        }
    </script>
@endsection