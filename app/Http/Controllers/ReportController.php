<?php

namespace App\Http\Controllers;

use App\Models\Government;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $government = auth()->user()->government;
        
        // Calculate various statistics
        $stats = $this->calculateStats($government);
        
        return view('reports.index', compact('government', 'stats'));
    }

    private function calculateStats(Government $government): array
    {
        // Resource statistics
        $resourceStats = [];
        foreach ($government->government_resources as $resource) {
            $capacity = config("game.resources.storage_capacity.{$resource->resource->name}", 1000);
            $percentage = min(100, ($resource->amount / $capacity) * 100);
            
            $resourceStats[$resource->resource->name] = [
                'amount' => $resource->amount,
                'capacity' => $capacity,
                'percentage' => $percentage,
                'usage' => $resource->population_usage ?? 0,
                'value' => $resource->amount * $resource->resource->price,
            ];
        }

        // Infrastructure statistics
        $infrastructureStats = [
            'total' => $government->government_infrastructures->count(),
            'total_level' => $government->government_infrastructures->sum('level'),
            'total_population_assigned' => $government->government_infrastructures->sum('population'),
            'total_production' => $government->government_infrastructures->sum('next_tick'),
            'by_type' => $government->government_infrastructures->groupBy('infrastructure.type')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total_level' => $group->sum('level'),
                    'total_population' => $group->sum('population'),
                    'total_production' => $group->sum('next_tick'),
                ];
            }),
        ];

        // Sector analysis
        $sectorStats = [
            'economy' => [
                'level' => $government->economy,
                'population' => $government->economy_population,
                'efficiency' => $government->economy_population > 0 ? $government->economy / $government->economy_population : 0,
            ],
            'health' => [
                'level' => $government->health,
                'population' => $government->health_population,
                'efficiency' => $government->health_population > 0 ? $government->health / $government->health_population : 0,
            ],
            'safety' => [
                'level' => $government->safety,
                'population' => $government->safety_population,
                'efficiency' => $government->safety_population > 0 ? $government->safety / $government->safety_population : 0,
            ],
            'education' => [
                'level' => $government->education,
                'population' => $government->education_population,
                'efficiency' => $government->education_population > 0 ? $government->education / $government->education_population : 0,
            ],
        ];

        // Financial analysis
        $financialStats = [
            'current_money' => $government->money,
            'interest_rate' => $government->calculateInterestAmount() / max(1, $government->money),
            'total_resource_value' => collect($resourceStats)->sum('value'),
            'maintenance_cost' => $government->government_infrastructures->sum(function($infra) {
                return $infra->level * config('game.economy.infrastructure_maintenance_cost', 0.001) * 1000;
            }),
        ];

        // Population analysis
        $populationStats = [
            'total' => $government->population,
            'available' => $government->available_population,
            'assigned' => $government->population - $government->available_population,
            'growth_rate' => $government->calculatePopulationChange(),
            'housing_capacity' => $government->government_infrastructures
                ->where('infrastructure.type', 'HOUSING')
                ->sum(function($infra) {
                    return $infra->level * 10; // 10 people per housing level
                }),
        ];

        return [
            'resources' => $resourceStats,
            'infrastructure' => $infrastructureStats,
            'sectors' => $sectorStats,
            'financial' => $financialStats,
            'population' => $populationStats,
        ];
    }
}
