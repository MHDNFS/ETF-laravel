@extends('layouts.layout')
@section('title', 'Customer Management')

@section('content')
    <div class="content page-animate">
        <!-- Header Section -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <div class="page-title">Customer Management</div>
                <div class="page-sub">Manage your customer database</div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-outline btn-sm" id="btn-export-csv">
                    <i class="fa-regular fa-circle-check"></i> Export CSV
                </button>
                <button class="btn btn-outline btn-sm" id="btn-export-pdf" style="color: #22c55e; border-color: #22c55e;">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </button>
                <button class="btn btn-blue btn-sm" id="btn-add-customer">
                    <i class="fa-solid fa-plus"></i> Add Customer
                </button>
            </div>
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

        <!-- Datatable Section -->
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <span class="card-title">All Customers (<span id="customer-count">0</span>)</span>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <!-- Custom Column Dropdown Button -->
                    <button class="btn btn-outline btn-sm">
                        Columns <i class="fa-solid fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i>
                    </button>

                    <!-- Custom Search Bar -->
                    <div class="header-search" style="margin: 0;">
                        <i class="fa-solid fa-magnifying-glass si"></i>
                        <input type="text" id="custom-searchBox" placeholder="Search..." style="width: 200px;">
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <!-- This table will be converted by DataTables -->
                <table id="customerTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="sort">ID</th>
                            <th class="sort">Name</th>
                            <th class="sort">Email</th>
                            <th class="sort">Phone</th>
                            <th class="sort">Address</th>
                            <th class="sort">Outstanding Balance</th>
                            <th class="sort">Vehicles</th>
                            <th class="sort">Last Transaction</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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