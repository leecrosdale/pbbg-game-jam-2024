<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentInfrastructure extends Model
{

    /** @use HasFactory<\Database\Factories\GovernmentInfrastructureFactory> */
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function government()
    {
        return $this->belongsTo(Government::class);
    }


    public function getNextTickAttribute()
    {

        $base = $this->infrastructure->base;
        $increase = $base * $this->level * pow($this->population, 0.001);
        $maxIncrease = 500;
        return floor(min($increase, $maxIncrease));

    }

    public function getUpgradeCostAttribute()
    {
        $base_cost = 100;
        $cost_multiplier = 1.2; // Reduced for a gentler cost increase
        $efficiency_cap = 100;  // Cap efficiency at 100 for cost calculation

        // Calculate adjusted efficiency for scaling
        $adjusted_efficiency = min($this->efficiency, $efficiency_cap);

        // Use a logarithmic or square root function for gradual cost increase
        $cost = $base_cost * (1 + ($cost_multiplier * sqrt($adjusted_efficiency)));

        return (int)$cost;
    }



    public function setPopulation(int $population)
    {

        $currentPopulation = $this->population;

        if ($population > $this->government->available_population ) {
            return false;
        }

        $remaining = $currentPopulation - $population;
        $this->population = $population;
        $this->save();

        if ($remaining > 0) {
            $this->government->available_population += $remaining;
        } else if ($remaining < 0) {
            $this->government->available_population -= abs($remaining);
        }

        $this->government->save();

        return true;

    }

}
