<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'create'])->name('login');

Route::redirect('/login', '/')->name('login.legacy');

Route::get('/dashboard', function () {
    return view('pages.index', [
        'activeSidebar' => 'dashboard',
    ]);
})->name('dashboard');

Route::redirect('/index', '/dashboard');

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
