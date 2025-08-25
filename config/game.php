<?php

return [

    'stats' => [
        'categories' => [
            'economy', 'health', 'safety', 'education'
        ],
        'increment_rate' => 0.05 // Reduced from 0.1 for more gradual progression
    ],
    'resources' => [
        'consumption_rates' => [
            'food' => 1.0,        // Increased from 0.5 - more realistic consumption
            'medicine' => 0.2,    // Increased from 0.1 - more realistic healthcare needs
            'electricity' => 1.5, // Increased from 1.0 - modern society needs more power
            'happiness' => 0.1,   // Increased from 0.05 - happiness is harder to maintain
            'clothing' => 0.3     // Increased from 0.2 - clothing wears out
        ],
        'storage_capacity' => [
            'food' => 1000,
            'medicine' => 500,
            'electricity' => 2000,
            'happiness' => 100,
            'clothing' => 800
        ]
    ],
    'settings' => [
        'starting_population' => 50,  // Increased from 10 for better starting balance
        'max_population_growth_rate' => 0.1, // Limit population growth to 10% per tick
        'interest_rate_cap' => 0.05,  // Cap interest at 5% instead of 20%
        'infrastructure_efficiency_decay' => 0.95, // Efficiency decreases with level
        'seasonal_impact_multiplier' => 0.5 // Reduced seasonal effects
    ],
    'economy' => [
        'starting_money' => 5000,
        'money_from_sectors' => 10, // Money generated per sector level
        'infrastructure_maintenance_cost' => 0.001 // Reduced from 0.01 to 0.1% of infrastructure cost per tick
    ]
];
