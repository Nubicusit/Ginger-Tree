<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/customers', function () {
    return view('pages.CustomerManagement');
})->name('customers');

Route::get('/leads', function () {
    return view('pages.LeadEnquiries');
})->name('leads');

