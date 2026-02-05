<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PathfinderController;

Volt::route('/', 'home.index');
Volt::route('/home', 'home.index')->name('home.index');



Volt::route('/pathfinder', 'pathfinder.index')->name('pathfinder.index');
Route::post('/pathfinder', [PathfinderController::class, 'store'])->name('pathfinder.store');

Volt::route('/pathfinder/create', 'pathfinder.create')->name('pathfinder.create');
