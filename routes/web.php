<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Events\TestSoketi;

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

Route::get('/opa', function () {
    broadcast(new TestSoketi("opa dev"));
});
