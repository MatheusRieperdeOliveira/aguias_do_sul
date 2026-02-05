<?php

namespace App\Http\Controllers;

use App\Http\Requests\PathfinderCreateRequest;
use App\Models\Pathfinder;
use App\Services\PathfinderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PathfinderController extends Controller
{
    public PathfinderService $service;
    public function __construct()
    {
        $this->service = new PathfinderService();
    }
    public function index()
    {
        $pathfinders = $this->service->getPathfinders();

        // return view('pages.pathfinder.index', compact('pathfinders'));
        return redirect()->route('pathfinder.index');
    }

    public function store(PathfinderCreateRequest $request): RedirectResponse
    {
        $this->service->storePathfinder($request->all());

        return redirect()
            ->route('pathfinder.index')
            ->with('success', 'Desbravador cadastrado');
    }
}
