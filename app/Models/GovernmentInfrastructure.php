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


    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }


    public function getNextTickAttribute()
    {

        $base = $this->infrastructure->base; // Base happiness per level
        $efficiency = $this->efficiency; // Scaling factor for population influence

        // Calculate Happiness
        $happiness = $base * $this->level * pow($this->population, $efficiency);

        // Optional: Limit maximum happiness for balance
        $max_happiness = 500;
        return floor(min($happiness, $max_happiness));

    }

    public function getUpgradeCostAttribute()
    {

        $base_cost = 100;
        $cost_multiplier = 1.5;

        // Calculate the cost for the next efficiency upgrade
        return (int)($base_cost * pow($cost_multiplier, $this->efficiency));
    }

}
