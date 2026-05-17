<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index', [
        'activeSidebar' => 'dashboard',
    ]);
});

// Legacy URL — same dashboard as / (sidebar and bookmarks may still use /index).
Route::get('/index', function () {
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
