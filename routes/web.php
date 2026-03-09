<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'pages.auth.login')->name('login');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Volt::route('/', 'pages.home.index');
    Volt::route('/home', 'pages.home.index')->name('home.index');

    Volt::route('/pathfinder', 'pages.pathfinder.index')->name('pathfinder.index');

    Volt::route('/unit', 'pages.unit.index')->name('unit.index');

    Volt::route('/events', 'pages.event.index')->name('event.index');

    Volt::route('/requirements', 'pages.requirement.index')->name('requirement.index');

    Route::group(["prefix" => "points"], function () {
        Volt::route('/pathfinders', 'pages.points.pathfinder')->name('points.pathfinder');
        Volt::route('/units', 'pages.points.unit')->name('points.unit');
    });

    Route::group(["prefix" => "profile"], function () {
        Volt::route('/settings', 'pages.profile.settings')->name('profile.settings');
    });
});
