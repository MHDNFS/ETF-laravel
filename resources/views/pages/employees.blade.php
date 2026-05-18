@extends('layouts.layout')
@section('title', 'Employees')
@section('header_title', 'Employees')

@section('content')
    <div class="content page-animate employees-page">

        <header class="employee-mgmt-head page-head-row">
            <div>
                <x-ui.page-header title="Employees" subtitle="Manage your employee database" />
            </div>
            <x-ui.page-action-toolbar
                class="employee-mgmt-toolbar"
                :show-export-csv="true"
                :show-export-pdf="true"
                :show-bulk-upload="true"
                :show-add-employee="true"
            />
        </header>

        <section class="card employee-filters-card" aria-label="Employee filters">
            <div class="card-header employee-filters-toggle" role="button" tabindex="0" onclick="toggleFilters()" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleFilters();}">
                <span class="card-title"><i class="fa-solid fa-filter"></i> Filters</span>
                <i class="fa-solid fa-chevron-down" id="filter-icon" aria-hidden="true"></i>
            </div>
            <div class="card-body employee-filters-body" id="filter-body" hidden>
                <p class="employee-filters-placeholder">Advanced filter options will appear here.</p>
            </div>
        </section>

        <section class="card employee-table-card" aria-label="Employee list">
            <div class="card-header employees-table-header">
                <span class="card-title">All Employees (<span id="employee-count">0</span>)</span>

                <div class="card-header-actions employees-table-toolbar">
                    <div class="employees-columns-dropdown" id="employee-columns-dropdown">
                        <button
                            type="button"
                            class="btn btn-outline btn-sm"
                            id="employee-columns-toggle"
                            aria-expanded="false"
                            aria-haspopup="true"
                            aria-controls="employee-columns-menu"
                        >
                            Columns
                            <i class="fa-solid fa-chevron-down employees-columns-chevron" id="employee-columns-chevron" aria-hidden="true"></i>
                        </button>
                        <div
                            class="employees-columns-menu"
                            id="employee-columns-menu"
                            role="menu"
                            hidden
                        >
                            <p class="employees-columns-menu__title">Show columns</p>
                            <div id="employee-columns-checkboxes"></div>
                        </div>
                    </div>

                    <div class="header-search employees-table-search">
                        <i class="fa-solid fa-magnifying-glass si"></i>
                        <input type="search" id="custom-searchBox" placeholder="Search employees…" aria-label="Search employees">
                    </div>
                </div>
            </div>

            <div class="table-wrap table-wrap--scroll-hint employees-table-scroll" id="employees-table-scroll">
                <table id="employeeTable" style="width: 100%;">
                    <tbody></tbody>
                </table>
            </div>
            <p id="employees-scroll-status" class="employees-scroll-status" hidden aria-live="polite"></p>
        </section>
    </div>

    <x-modals.add-employee-modal />
    <x-modals.edit-employee-modal />
    <x-modals.bulk-upload-employees-modal />

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

