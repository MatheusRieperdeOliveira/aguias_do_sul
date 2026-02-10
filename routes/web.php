<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PathfinderController;

Volt::route('/', 'pages.home.index');
Volt::route('/home', 'pages.home.index')->name('home.index');

Volt::route('/pathfinder', 'pages.pathfinder.index')->name('pathfinder.index');

Volt::route('/unit', 'pages.unit.index')->name('unit.index');

Volt::route('/events', 'pages.event.index')->name('event.index');
