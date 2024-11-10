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


        $availableInfrastructures = Infrastructure::whereNotIn('id', $infrastructures->pluck('infrastructure_id'))->get();


        return view('infrastructures.index', compact('infrastructures', 'government', 'availableInfrastructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function upgrade(Request $request, GovernmentInfrastructure $governmentInfrastructure)
    {

        $user = auth()->user();
        $government = $user->government;

        $cost = $governmentInfrastructure->upgrade_cost;

        if ($cost > $government->money) {
            return redirect()->back()->withErrors('Not enough money');
        }

        $government->money -= $cost;
        $government->save();

        $governmentInfrastructure->level += 1;
        $governmentInfrastructure->save();

        return redirect()->back()->with('status', 'Upgraded');


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'infrastructure' => 'required|uuid'
        ]);


        $infrastructure = Infrastructure::where('uuid', $request->infrastructure)->firstOrFail();

        $cost = $infrastructure->cost;

        $user = auth()->user();
        $government = $user->government;

        if ($government->money < $cost) {
            return redirect()->back()->withErrors('Not enough money to buy ' . $infrastructure->name);
        }


        $government->money -= $cost;
        $government->save();


        $government->government_infrastructures()->updateOrCreate([
           'infrastructure_id' => $infrastructure->id,
        ]);

        return redirect()->back()->with('status', 'Purchased ' . $infrastructure->name);

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
