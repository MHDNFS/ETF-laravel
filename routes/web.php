<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// in here i put index page is loading with side bar access,,
Route::get('/index', function () {
    // Pass the active sidebar key so the dashboard link is highlighted.
    // This key must match the sidebar item's 'key' value.
    return view('pages.index', [
        'activeSidebar' => 'dashboard',
    ]);
});

// we can call like this for any pages in sidebar
Route::get('/profile', function () {
    return view('pages.profile', [
        'activeSidebar' => 'profile',
    ]);
});

Route::get('/etf-calculator', function () {
    return view('pages.etf-calculator', [
        'activeSidebar' => 'etf-calculator',
    ]);
});

Route::get('/etf-funds', function () {
    return view('pages.etf-funds', [
        'activeSidebar' => 'etf-funds',
    ]);
});

Route::get('/customer-management', function () {
    return view('pages.customer-management', [
        'activeSidebar' => 'customer-management',
    ]);
});

Route::get('/ptf-portfolio', function () {
    return view('pages.ptf-portfolio', [
        'activeSidebar' => 'ptf-portfolio',
    ]);
});

Route::get('/reports', function () {
    return view('pages.reports', [
        'activeSidebar' => 'reports',
    ]);
});

Route::get('/settings', function () {
    return view('pages.settings', [
        'activeSidebar' => 'settings',
    ]);
});
