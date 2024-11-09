<?php

namespace App\Http\Controllers;

use App\Models\Government;
use Illuminate\Http\Request;

class GovernmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $government = $user->government;

        $population = $government->population;


        return view('dashboard', compact('government', 'population'));
    }


    public function populationUpdate(Request $request)
    {

        $request->validate([
            'economy' => 'required|min:0|integer',
            'health' => 'required|min:0|integer',
            'safety' => 'required|min:0|integer',
            'education' => 'required|min:0|integer',
        ]);


        $user = auth()->user();

        $government = $user->government;

        $economy = (int) $request->economy;
        $health = (int) $request->health;
        $safety = (int) $request->safety;
        $education = (int) $request->education;

        $government->updatePopulationAllocation($economy, $health, $safety, $education);

        return redirect()->back()->with('status', 'Government population allocation updated');


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
    public function show(Government $government)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Government $government)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Government $government)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Government $government)
    {
        //
    }
}
