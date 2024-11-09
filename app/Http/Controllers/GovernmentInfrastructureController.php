<?php

namespace App\Http\Controllers;

use App\Models\GovernmentInfrastructure;
use App\Models\Infrastructure;
use Illuminate\Http\Request;

class GovernmentInfrastructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $government = $user->government;
        $infrastructures = $government->government_infrastructures;


        $unbuiltInfrastructures = Infrastructure::whereNotIn('id', $infrastructures->pluck('infrastructure_id'))->get();


        return view('government.index', compact('infrastructures', 'government', 'unbuiltInfrastructures'));
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
    public function show(GovernmentInfrastructure $governmentInfrastructure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GovernmentInfrastructure $governmentInfrastructure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GovernmentInfrastructure $governmentInfrastructure)
    {

        $request->validate([
            'population' => 'required|integer|min:0',
        ]);


        $population = (int) $request->population;

        if (!$governmentInfrastructure->setPopulation($population)) {
            return redirect()->back()->withErrors("Not enough population available.");
        }

        return redirect()->back()->with('status', "Set Population to {$population}.");


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GovernmentInfrastructure $governmentInfrastructure)
    {
        //
    }
}
