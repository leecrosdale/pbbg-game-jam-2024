<?php

namespace App\Http\Controllers;

use App\Models\Government;

class GovernmentLeaderboardController extends Controller
{
    public function index()
    {

        $overallGovernments = Government::query()
            ->orderByDesc('overall')
            ->take(10)
            ->get();

        $educationGovernments = Government::query()
            ->orderByDesc('education')
            ->take(10)
            ->get();

        $healthGovernments = Government::query()
            ->orderByDesc('health')
            ->take(10)
            ->get();

        $safetyGovernments = Government::query()
            ->orderByDesc('safety')
            ->take(10)
            ->get();

        $economyGovernments = Government::query()
            ->orderByDesc('economy')
            ->take(10)
            ->get();


        return view('leaderboard.index', compact('overallGovernments', 'educationGovernments', 'healthGovernments', 'safetyGovernments', 'economyGovernments'));
    }
}
