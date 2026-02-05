<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PathfinderController;

Volt::route('/', 'pages.home.index');
Volt::route('/home', 'pages.home.index')->name('home.index');



Volt::route('/pathfinder', 'pages.pathfinder.index')->name('pathfinder.index');
Route::post('/pathfinder', [PathfinderController::class, 'store'])->name('pathfinder.store');

Volt::route('/pathfinder/create', 'pages.pathfinder.create')->name('pathfinder.create');

Volt::route('/unit', 'pages.unit.index')->name('unit.index');
