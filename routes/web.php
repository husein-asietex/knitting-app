<?php

use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');

Route::livewire('/', 'pages::auth.login')->name('login')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard')->middleware('can:access-dashboard');
    Route::livewire('/knitting1', 'pages::dashboard.knitting1')->name('knitting1')->middleware('can:access-dashboard');
    Route::livewire('/users', 'pages::dashboard.users')->name('users')->middleware('can:access-dashboard');
    Route::livewire('/machines', 'pages::dashboard.machines')->name('machines')->middleware('can:access-dashboard');
    Route::livewire('/shifts', 'pages::dashboard.shifts')->name('shifts')->middleware('can:access-dashboard');
    Route::livewire('/teams', 'pages::dashboard.teams')->name('teams')->middleware('can:access-dashboard');
    Route::livewire('/sections', 'pages::dashboard.sections')->name('sections')->middleware('can:access-dashboard');
    Route::livewire('/machine-operators', 'pages::dashboard.machine-operators')->name('machine-operators')->middleware('can:access-dashboard');
});

