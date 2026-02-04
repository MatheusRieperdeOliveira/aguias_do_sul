<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PathfinderController extends Controller
{
    public function index()
    {
        return view('pages.pathfinder.index');
    }
}
