<?php

namespace App\Http\Controllers;

use App\Models\GovernmentResource;
use App\Models\Infrastructure;
use App\Models\Resource;
use Illuminate\Http\Request;

class GovernmentResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $government = $user->government;
        $resources = $government->government_resources;

        $ungatheredResources = Resource::whereNotIn('id', $resources->pluck('resource_id'))->get();


        return view('resources.index', compact('resources', 'government', 'ungatheredResources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GovernmentResource $governmentResource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GovernmentResource $governmentResource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GovernmentResource $governmentResource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GovernmentResource $governmentResource)
    {
        //
    }
}
